<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Jama;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payjama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $res = [];
        $clauses = "";
        $order_code = $this->generateCode("Order", '#O');
        if (isset($request->fromOrder)) {
            $res['orderCode'] = $order_code;
            return response()->json($res);
        }
        if (isset($request->dateFrom) && $request->dateFrom != '') {
            $clauses .= " AND om.orderDate BETWEEN '$request->dateFrom' AND '$request->dateTo'";
        }
        if (isset($request->customerId) && $request->customerId != '') {
            $clauses .= " AND om.customer_id = '$request->customerId'";
        }
        if (isset($request->id) && $request->id != '') {
            $clauses .= " AND om.id = '$request->id'";

            $orderDetails = OrderItem::with('jama', 'payjama', 'product')->where('order_id', $request->id)->get();
            $res['orderItem'] = $orderDetails;
        }

        $orders = DB::select("SELECT
                            om.*,
                            c.name,
                            c.customer_code,
                            c.phone
                        FROM orders om
                        LEFT JOIN customers c ON c.id = om.customer_id
                        WHERE om.deleted_at IS NULL $clauses");
        $res['orders'] = $orders;

        return response()->json($res);
    }

    public function manage()
    {
        return view("pages.order.manage");
    }

    public function create($id = null)
    {
        return view("pages.order.create", compact('id'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $customer_check = Customer::where('phone', $request->order['customer_mobile'])->first();
            if (empty($customer_check)) {
                $customer                = new Customer();
                $customer->customer_code = $this->generateCode('Customer', 'C');
                $customer->name          = $request->order['customer_name'];
                $customer->phone         = $request->order['customer_mobile'];
                $customer->save();
                $customerId = $customer->id;
            } else {
                $customerId = $customer_check->id;
            }

            $data                  = new Order();
            $data->order_code      = $request->order['order_code'];
            $data->orderDate       = $request->order['orderDate'];
            $data->deliveryDate    = $request->order['deliveryDate'];
            $data->customer_id     = $customerId;
            $data->refer           = $request->order['refer'];
            $data->subtotal        = $request->order['subtotal'];
            $data->discount        = $request->order['discount'];
            $data->total           = $request->order['total'];
            $data->advance         = $request->order['advance'];
            $data->due             = $request->order['due'];
            $data->tailor_slip_one = $request->order['tailor_slip_one'];
            $data->tailor_slip_two = $request->order['tailor_slip_two'];
            $data->save();

            foreach ($request->carts as $item) {
                if ($item['category_id'] == 1) {
                    $payjama                   = new Payjama();
                    $payjama->long             = $item['payjama']['long'];
                    $payjama->komor            = $item['payjama']['komor'];
                    $payjama->mohori           = $item['payjama']['mohori'];
                    $payjama->high             = $item['payjama']['high'];
                    $payjama->ran              = $item['payjama']['ran'];
                    $payjama->pocket_chain     = $item['payjama']['pocket_chain'];
                    $payjama->good_runner      = $item['payjama']['good_runner'];
                    $payjama->back_pocket      = $item['payjama']['back_pocket'];
                    $payjama->pocket_chain_one = $item['payjama']['pocket_chain_one'];
                    $payjama->fitha            = $item['payjama']['fitha'];
                    $payjama->rabar            = $item['payjama']['rabar'];
                    $payjama->save();

                    $payjamaId = $payjama->id;
                }
                if ($item['category_id'] == 2) {
                    $jama            = new Jama();
                    $jama->long      = $item['jama']['long'];
                    $jama->body      = $item['jama']['body'];
                    $jama->tira      = $item['jama']['tira'];
                    $jama->hata      = $item['jama']['hata'];
                    $jama->mohori    = $item['jama']['mohori'];
                    $jama->gola      = $item['jama']['gola'];
                    $jama->gher      = $item['jama']['gher'];
                    $jama->plate     = $item['jama']['plate'];
                    $jama->mora      = $item['jama']['mora'];
                    $jama->ghari     = $item['jama']['ghari'];
                    $jama->peter_map = $item['jama']['peter_map'];
                    $jama->save();

                    $jamaId = $jama->id;
                }

                $orderitem               = new OrderItem();
                $orderitem->order_id     = $data->id;
                $orderitem->product_id   = $item['product_id'];
                $orderitem->quantity     = $item['quantity'];
                $orderitem->retail_price = $item['retail_price'];
                $orderitem->tailor_price = $item['tailor_price'];
                $orderitem->jama_id      = isset($jamaId) ? $jamaId : null;
                $orderitem->payjama_id   = isset($payjamaId) ? $payjamaId : null;
                $orderitem->save();
            }

            DB::commit();
            return response()->json(['status' => true, 'msg' => "অর্ডার যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $customer_check = Customer::where('phone', $request->order['customer_mobile'])->first();
            if (empty($customer_check)) {
                $customer                = new Customer();
                $customer->customer_code = $this->generateCode('Customer', 'C');
                $customer->name          = $request->order['customer_name'];
                $customer->phone         = $request->order['customer_mobile'];
                $customer->save();
                $customerId = $customer->id;
            } else {
                $customerId = $customer_check->id;
            }

            $data                  = Order::find($request->order['id']);
            $data->order_code      = $request->order['order_code'];
            $data->orderDate       = $request->order['orderDate'];
            $data->deliveryDate    = $request->order['deliveryDate'];
            $data->customer_id     = $customerId;
            $data->refer           = $request->order['refer'];
            $data->subtotal        = $request->order['subtotal'];
            $data->discount        = $request->order['discount'];
            $data->total           = $request->order['total'];
            $data->advance         = $request->order['advance'];
            $data->due             = $request->order['due'];
            $data->tailor_slip_one = $request->order['tailor_slip_one'];
            $data->tailor_slip_two = $request->order['tailor_slip_two'];
            $data->save();

            $oldItem = OrderItem::where('order_id', $data->id)->get();
            foreach ($oldItem as $item) {
                if (!empty($item->jama_id)) {
                    Jama::where('id', $item->jama_id)->first()->delete();
                }
                if (!empty($item->payjama_id)) {
                    Payjama::where('id', $item->payjama_id)->first()->delete();
                }
                OrderItem::where('id', $item->id)->first()->forceDelete();
            }
            foreach ($request->carts as $item) {
                if ($item['category_id'] == 1) {
                    $payjama                   = new Payjama();
                    $payjama->long             = $item['payjama']['long'];
                    $payjama->komor            = $item['payjama']['komor'];
                    $payjama->mohori           = $item['payjama']['mohori'];
                    $payjama->high             = $item['payjama']['high'];
                    $payjama->ran              = $item['payjama']['ran'];
                    $payjama->pocket_chain     = $item['payjama']['pocket_chain'];
                    $payjama->good_runner      = $item['payjama']['good_runner'];
                    $payjama->back_pocket      = $item['payjama']['back_pocket'];
                    $payjama->pocket_chain_one = $item['payjama']['pocket_chain_one'];
                    $payjama->fitha            = $item['payjama']['fitha'];
                    $payjama->rabar            = $item['payjama']['rabar'];
                    $payjama->save();

                    $payjamaId = $payjama->id;
                }
                if ($item['category_id'] == 2) {
                    $jama            = new Jama();
                    $jama->long      = $item['jama']['long'];
                    $jama->body      = $item['jama']['body'];
                    $jama->tira      = $item['jama']['tira'];
                    $jama->hata      = $item['jama']['hata'];
                    $jama->mohori    = $item['jama']['mohori'];
                    $jama->gola      = $item['jama']['gola'];
                    $jama->gher      = $item['jama']['gher'];
                    $jama->plate     = $item['jama']['plate'];
                    $jama->mora      = $item['jama']['mora'];
                    $jama->ghari     = $item['jama']['ghari'];
                    $jama->peter_map = $item['jama']['peter_map'];
                    $jama->save();

                    $jamaId = $jama->id;
                }

                $orderitem               = new OrderItem();
                $orderitem->order_id     = $data->id;
                $orderitem->product_id   = $item['product_id'];
                $orderitem->quantity     = $item['quantity'];
                $orderitem->retail_price = $item['retail_price'];
                $orderitem->tailor_price = $item['tailor_price'];
                $orderitem->jama_id      = isset($jamaId) ? $jamaId : null;
                $orderitem->payjama_id   = isset($payjamaId) ? $payjamaId : null;
                $orderitem->save();
            }

            DB::commit();
            return response()->json(['status' => true, 'msg' => "অর্ডার আপডেট করা হয়েছে।"]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Order::find($request->id);
            $orderitem = OrderItem::where('order_id', $data->id)->first();
            $orderitem->delete();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "অর্ডার মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
