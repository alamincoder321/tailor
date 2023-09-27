<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Setting;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return Setting::first();
    }

    public function create()
    {
        $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
            ->pluck('permissions')
            ->toArray();
        if (!in_array("updateSetting", $access)) {
            return view("pages.unauthorize");
        }
        return view("pages.setting.create");
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data               = Setting::first();
            $data->company_name = $request->company_name;
            $data->mobile       = $request->mobile;
            $data->email        = $request->email;
            $data->address      = $request->address;
            $data->updated_at   = Carbon::now();
            if ($request->hasFile('logo')) {
                if (File::exists($data->logo)) {
                    File::delete($data->logo);
                }
                $data->logo         = $this->imageUpload($request, 'logo', 'uploads/logo');
            }
            if ($request->hasFile('navicon')) {
                if (File::exists($data->navicon)) {
                    File::delete($data->navicon);
                }
                $data->navicon      = $this->imageUpload($request, 'navicon', 'uploads/navicon');
            }
            $data->update();
            return response()->json(['status' => true, 'msg' => "সেটিং আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
