<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Designation::latest()->get();
    }

    public function create()
    {
        return view("pages.designation.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data             = new Designation();
            $data->name       = $request->name;
            $data->created_at = Carbon::now();
            $data->save();
            return response()->json(['status' => true, 'msg' => "পদবী যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data = Designation::find($request->id);
            $data->name = $request->name;
            $data->updated_at = Carbon::now();
            $data->update();
            return response()->json(['status' => true, 'msg' => "পদবী আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Designation::where($request->id)->first();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "পদবী মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
