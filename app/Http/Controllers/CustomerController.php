<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
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
        // $validator = Validator::make($request->all(), [
        //     'image'        => 'nullable|mimes:jpg,png,gif',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        // }

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
}
