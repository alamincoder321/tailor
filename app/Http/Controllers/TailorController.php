<?php

namespace App\Http\Controllers;

use App\Models\Tailor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TailorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view("pages.tailor.create");
    }

    public function index()
    {
        $data = Tailor::latest()->get();
        return $data;
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'username' => 'required|string|min:3|max:50|unique:users',
            'email'    => 'required|email:rfc,dns|unique:users',
            'image'    => 'nullable|mimes:jpeg,png,jpg,gif',
            'password' => 'required|min:5|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {
            DB::beginTransaction();

            //user
            $user           = new User();
            $user->name     = $request->name;
            $user->username = $request->username;
            $user->email    = $request->email;
            $user->role_id  = 3;
            $user->password = Hash::make($request->password);
            if ($request->hasFile('image')) {
                $user->image      = $this->imageUpload($request, 'image', 'uploads/user');
            }
            $user->save();

            $data             = new Tailor();
            $data->name       = $request->name;
            $data->username   = $request->username;
            $data->email      = $request->email;
            $data->mobile     = $request->mobile;
            $data->address    = $request->address;
            $data->experience = $request->experience;
            $data->user_id    = $user->id;
            if ($request->hasFile('image')) {
                $data->image      = $this->imageUpload($request, 'image', 'uploads/tailor');
            }
            $data->save();

            DB::commit();

            return response()->json(['status' => true, 'msg' => "কারিগর যুক্ত করা হয়েছে।"]);
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
        $data = Tailor::find($request->id);
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'username' => 'required|string|min:3|max:50|unique:users,username,' . $data->user_id,
            'email'    => 'required|email:rfc,dns|unique:users,email,' . $data->user_id,
            'password' => 'nullable|min:5|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {
            $user           = User::find($data->user_id);
            $user->name     = $request->name;
            $user->username = $request->username;
            $user->email    = $request->email;
            $user->role_id  = 3;
            $user->password = Hash::make($request->password);
            $user->updated_at = Carbon::now();
            if ($request->hasFile('image')) {
                if (File::exists($user->image)) {
                    File::delete($user->image);
                }
                $user->image      = $this->imageUpload($request, 'image', 'uploads/user');
            }
            $user->update();
            
            //=========== Tailor update ==================
            $data->name       = $request->name;
            $data->username   = $request->username;
            $data->email      = $request->email;
            $data->mobile     = $request->mobile;
            $data->address    = $request->address;
            $data->experience = $request->experience;
            $data->updated_at = Carbon::now();
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image      = $this->imageUpload($request, 'image', 'uploads/tailor');
            }

            $data->update();
            return response()->json(['status' => true, 'msg' => "কারিগর আপডেট করা হয়েছে।"]);
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
            $data = Tailor::find($request->id);
            $user = User::find($data->user_id);
            $user->delete();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "কারিগর মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
