<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClothingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        try {
            $clauses = "";
            if (isset($request->dateFrom) && $request->dateFrom != '') {
                $clauses .= " AND o.orderDate BETWEEN '$request->dateFrom' AND '$request->dateTo'";
            }
            if (isset($request->tailorId) && $request->tailorId != '') {
                $clauses .= " AND ot.tailor_id = '$request->tailorId'";
            }
            if (isset($request->status) && $request->status != '') {
                $clauses .= " AND ot.status = '$request->status'";
            }

            $detail = DB::select("SELECT
                            ot.*,
                            p.name as product_name,
                            t.name as tailor_name,
                            o.orderDate,
                            o.customer_id,
                            c.name as customer_name
                        FROM order_items ot
                        LEFT JOIN products p ON p.id = ot.product_id
                        LEFT JOIN tailors t ON t.id = ot.tailor_id
                        LEFT JOIN orders o ON o.id = ot.order_id
                        LEFT JOIN customers c ON c.id = o.customer_id
                        WHERE ot.status != 'complete' 
                        AND ot.status != 'cancel'
                        AND ot.tailor_id is not null
                        $clauses
                        ");

            return response()->json(['status' => false, 'msg' => $detail]);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function create($id = null)
    {
        if (Auth::guard('web')->user()->role->name != 'SuperAdmin') {
            $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
                ->pluck('permissions')
                ->toArray();
            if (!in_array("clothingEntry", $access)) {
                return view("pages.unauthorize");
            }
        }

        return view("pages.clothing.create", compact('id'));
    }

    public function manage()
    {
        if (Auth::guard('web')->user()->role->name != 'SuperAdmin') {
            $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
                ->pluck('permissions')
                ->toArray();
            if (!in_array("clothingList", $access)) {
                return view("pages.unauthorize");
            }
        }

        return view("pages.clothing.manage");
    }

    public function store(Request $request)
    {
        try {
            $data = OrderItem::where("id", $request->id)->first();
            $data->tailor_id = $request->tailor_id;
            $data->updated_at = Carbon::now();
            $data->save();
            return response()->json(['status' => true, 'msg' => "Clothing assign on Tailor Successfully"]);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function statusChange(Request $request)
    {
        try {
            $data = OrderItem::where('id', $request->id)->first();
            $orderId = $data->order_id;

            if ($request->status == 'complete') {
                $data->tailor_total = $request->billAmount;
                $data->paid         = $request->paidAmount;
                $data->due          = $request->dueAmount;
            }
            $data->status = $request->status;
            $data->update();

            $msg = "";
            if ($request->status == 'proccess') {
                $msg = 'Work proccessing successfully';
            }
            if ($request->status == 'complete') {
                $msg = 'Work complete successfully';
            }
            $orderdetail = OrderItem::where('order_id', $orderId)->get();
            $ordercomplete = OrderItem::where('order_id', $orderId)->where('status', 'complete')->get();
            if (count($orderdetail) == count($ordercomplete)) {
                $order = Order::where('id', $orderId)->first();
                $order->status = 'complete';
                $order->update();
            }

            return response()->json(['status' => false, 'msg' => $msg]);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
    }
}
