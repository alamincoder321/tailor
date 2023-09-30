<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Brand::latest()->get();
    }

    public function create()
    {
        if (Auth::guard('web')->user()->role->name != 'SuperAdmin') {
            $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
                ->pluck('permissions')
                ->toArray();
            if (!in_array("brandEntry", $access)) {
                return view("pages.unauthorize");
            }
        }
        
        return view("pages.brand.create");
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
            $data = new Brand();
            $data->name = $request->name;
            $data->created_at = Carbon::now();
            $data->save();
            return response()->json(['status' => true, 'msg' => "ব্র্যান্ড যুক্ত করা হয়েছে।"]);
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
            $data             = Brand::find($request->id);
            $data->name       = $request->name;
            $data->updated_at = Carbon::now();
            $data->update();
            return response()->json(['status' => true, 'msg' => "ব্র্যান্ড আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Brand::where('id', $request->id)->first();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "ব্র্যান্ড মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
