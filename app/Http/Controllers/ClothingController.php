<?php

namespace App\Http\Controllers;

use App\Models\Clothing;
use App\Models\ClothingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClothingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $clauses = "";
        if (isset($request->dateFrom) && $request->dateFrom != '') {
            $clauses .= " AND ct.date BETWEEN '$request->dateFrom' AND '$request->dateTo'";
        }
        if (isset($request->tailorId) && $request->tailorId != '') {
            $clauses .= " AND ct.tailor_id = '$request->tailorId'";
        }
        if (isset($request->id) && $request->id != '') {
            $clauses .= " AND ct.id = '$request->id'";

            $clothingDetails = ClothingItem::with('product')->where('clothing_id', $request->id)->get();
            $res['clothingItem'] = $clothingDetails;
        }

        $clothing = DB::select("SELECT
                            ct.*,
                            t.name,
                            t.mobile
                        FROM clothing ct
                        LEFT JOIN tailors t ON t.id = ct.tailor_id
                        WHERE ct.deleted_at IS NULL $clauses");
        $res['clothing'] = $clothing;

        return response()->json($res);
    }

    public function create($id = null)
    {
        return view("pages.clothing.create", compact('id'));
    }

    public function manage()
    {
        return view("pages.clothing.manage");
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $cloth = $request->clothing;

            $data             = new Clothing();
            $data->tailor_id  = $cloth['tailor_id'];
            $data->date       = $cloth['date'];
            $data->total      = $cloth['total'];
            $data->paid       = $cloth['paid'];
            $data->due        = $cloth['due'];
            $data->addby      = Auth::user()->name;
            $data->note       = $cloth['note'];
            $data->created_at = Carbon::now();
            $data->save();

            foreach ($request->carts as $key => $val) {
                $item               = new ClothingItem();
                $item->clothing_id  = $data->id;
                $item->product_id   = $val['product_id'];
                $item->category_id  = $val['category_id'];
                $item->quantity     = $val['quantity'];
                $item->tailor_price = $val['tailor_price'];
                $item->total        = $val['total'];
                $item->save();
            }

            DB::commit();

            return response()->json(['status' => true, 'msg' => "ক্লোথিং যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $cloth = $request->clothing;

            $data             = Clothing::find($cloth['id']);
            $data->tailor_id  = $cloth['tailor_id'];
            $data->date       = $cloth['date'];
            $data->total      = $cloth['total'];
            $data->paid       = $cloth['paid'];
            $data->due        = $cloth['due'];
            $data->addby      = Auth::user()->name;
            $data->note       = $cloth['note'];
            $data->updated_at = Carbon::now();
            $data->update();

            ClothingItem::where('clothing_id', $cloth['id'])->delete();

            foreach ($request->carts as $key => $val) {
                $item               = new ClothingItem();
                $item->clothing_id  = $cloth['id'];
                $item->product_id   = $val['product_id'];
                $item->category_id  = $val['category_id'];
                $item->quantity     = $val['quantity'];
                $item->tailor_price = $val['tailor_price'];
                $item->total        = $val['total'];
                $item->save();
            }

            DB::commit();

            return response()->json(['status' => true, 'msg' => "ক্লোথিং আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Clothing::where('id', $request->id)->first();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "ক্লোথিং মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
