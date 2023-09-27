<?php

namespace App\Http\Controllers;

use App\Models\UserAccess;
use Illuminate\Http\Request;
use App\Models\TailorPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TailorPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tailorpayments = TailorPayment::with('tailor')->latest()->get();

        return response()->json($tailorpayments);
    }

    public function create()
    {
        $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
            ->pluck('permissions')
            ->toArray();
        if (!in_array("tailorPayment", $access)) {
            return view("pages.unauthorize");
        }
        return view("pages.tailorpayment.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data                   = new TailorPayment();
            $data->date             = $request->date;
            $data->transaction_type = $request->transaction_type;
            $data->payment_type     = $request->payment_type;
            $data->bank_id          = $request->bank_id;
            $data->tailor_id        = $request->tailor_id;
            $data->amount           = $request->amount;
            $data->due              = $request->due;
            $data->note             = $request->note;
            $data->addby            = Auth::user()->name;
            $data->created_at       = Carbon::now();
            $data->save();

            return response()->json(['status' => true, 'msg' => "কারিগর পেমেন্ট সফল হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data                   = TailorPayment::find($request->id);
            $data->date             = $request->date;
            $data->transaction_type = $request->transaction_type;
            $data->payment_type     = $request->payment_type;
            $data->bank_id          = $request->bank_id;
            $data->tailor_id        = $request->tailor_id;
            $data->amount           = $request->amount;
            $data->due              = $request->due;
            $data->note             = $request->note;
            $data->updated_at       = Carbon::now();
            $data->update();
            return response()->json(['status' => true, 'msg' => "কারিগর পেমেন্ট আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = TailorPayment::where('id', $request->id)->first();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "কারিগর পেমেন্ট মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
