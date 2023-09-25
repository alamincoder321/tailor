<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("dashboard");
    }


    // user profile
    public function profile()
    {
        $data = Auth::user();
        return view('pages.profile', compact('data'));
    }

    public function profileUpdate(Request $request)
    {
        try {
            $user = Auth::user();
            if (!empty($request->old_password) || !empty($request->new_password) || !empty($request->confrim_password)) {
                $validator = Validator::make($request->all(), [
                    "name"             => "required",
                    "username"         => "required|unique:users,username," . $user->id,
                    "email"            => "required|unique:users,email," . $user->id,
                    "old_password"     => "required",
                    "new_password"     => "required",
                    'confirm_password' => 'required_with:new_password|same:new_password'
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    "name"     => "required",
                    "username" => "required|unique:users,username," . $user->id,
                    "email"    => "required|unique:users,email," . $user->id,
                ]);
            }

            if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'error' => $validator->errors()]);
            }

            $data = User::find($user->id);
            if (!empty($request->old_password) || !empty($request->new_password) || !empty($request->confrim_password)) {
                if (Hash::check($request->old_password, $user->password)) {
                    $data->password = Hash::make($request->new_password);
                } else {
                    return response()->json(["errors" => "Old password does not match"]);
                }
            }
            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->save();
            return response()->json(['status' => true, 'msg' => "ইউজার আপডেট করা হয়েছে।"]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function imageUpdate(Request $request)
    {
        try {

            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                "image" => "mimes:jpg,png,jpeg"
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
            }
            $data = User::find($user->id);
            $old = $data->image;

            if ($request->hasFile('image')) {
                if (File::exists($old)) {
                    File::delete($old);
                }
                $data->image = $this->imageUpload($request, 'image', 'uploads/user');
            }

            $data->save();
            return response()->json(['status' => true, 'msg' => "ইউজার ইমেজ আপডেট করা হয়েছে।"]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
