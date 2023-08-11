<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\ModelTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customer_code = $this->generateCode('Customer', 'C');
        $customers = Customer::latest()->get();

        return response()->json(["customers" => $customers, "customer_code" => $customer_code]);
    }

    public function create()
    {
        return view("pages.customer.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'        => 'nullable|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {

            $data                = new Customer();
            $data->customer_code = $request->customer_code;
            $data->name          = $request->name;
            $data->phone         = $request->phone;
            $data->email         = $request->email;
            $data->address       = $request->address;
            if ($request->hasFile('image')) {
                $data->image      = $this->imageUpload($request, 'image', 'uploads/customer');
            }
            $data->save();

            return response()->json(['status' => true, 'msg' => "কাস্টমার যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        try {

            $data             = Customer::find($request->id);
            $data->name       = $request->name;
            $data->phone      = $request->phone;
            $data->email      = $request->email;
            $data->address    = $request->address;
            $data->updated_at = Carbon::now();
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image      = $this->imageUpload($request, 'image', 'uploads/customer');
            }
            $data->update();

            return response()->json(['status' => true, 'msg' => "কাস্টমার আপডেট করা হয়েছে।"]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Customer::find($request->id);
            $data->delete();
            return response()->json(['status' => true, 'msg' => "কাস্টমার মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }


    // extra method
    public function mobileCheck(Request $request)
    {
        return Customer::where('phone', $request->phone)->get();
    }

    public function customerDue(Request $request)
    {
        return ModelTable::customerDue($request->id);
    }

    public function customerLedger()
    {
        return view('pages.customer.ledger');
    }

    // ledger
    public function getcustomerLedger(Request $request)
    {
        $cusDue = DB::select("SELECT c.previous_due AS previousDue FROM customers c WHERE c.id = '$request->id'");

        $result = DB::select("SELECT
                        'a' AS sequence,
                        date(om.created_at) AS date,
                        concat('Order Invoice: ',om.order_code) AS description,
                        om.total AS billAmount,
                        om.advance AS paidAmount,
                        om.due AS dueAmount
                        FROM orders om
                        WHERE om.customer_id = '$request->id'
                        
                    UNION
                        SELECT
                        'b' AS sequence,
                        cp.date AS date,
                        'Customer Payment' AS description,
                        0.00 AS billAmount,
                        cp.amount AS paidAmount,
                        0.00 AS dueAmount
                        FROM customer_payments cp
                        WHERE cp.customer_id = '$request->id'
                        ORDER BY date, sequence ASC");

        $ledger = array_map(function ($key, $row) use ($result) {
            $row->due = $key == 0 ? $row->billAmount - $row->paidAmount : ($result[$key - 1]->due + ($row->billAmount - $row->paidAmount));
            return $row;
        }, array_keys($result), $result);

        $preDue = $cusDue[0]->previousDue;
        if (count($ledger) > 0) {
            foreach($ledger as $val){
                $val->due += $preDue;
            }
        }

        $previousRows = array_filter($ledger, function ($row) use ($request) {
            return $row->date < $request->dateFrom;
        });
        $previousDue = empty($previousRows) ? $preDue : end($previousRows)->due;

        return ["ledger" => $ledger, "previousDue" => $previousDue];
    }
}
