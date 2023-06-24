<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tailor;
use Illuminate\Http\Request;
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
            'name'     => 'required|string|min:3|max:30',
            'username' => 'required|string|min:3|max:20|unique:users',
            'email'    => 'required|email:rfc,dns|unique:users',
            'role_id'  => 'required|string',
            'image'    => 'nullable|mimes:jpeg,png,jpg,gif',
            'password' => 'required|min:5|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {
            $data = new Tailor();
            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->role_id  = $request->role_id;
            $data->password = Hash::make($request->password);
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image      = $this->imageUpload($request, 'image', 'uploads/tailor');
            }
            $data->created_at = Carbon::now();
            $data->save();

            return response()->json(['status' => true, 'msg' => "কারিগর যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|min:3|max:30',
            'username' => 'required|string|min:3|max:20|unique:users,username,' . $request->id,
            'email'    => 'required|email:rfc,dns|unique:users,email,' . $request->id,
            'role_id'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {
            $data = Tailor::find($request->id);

            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->phone    = $request->phone;
            $data->address    = $request->address;
            $data->experience  = $request->experience;
            if (!empty($request->password)) {
                $data->password = Hash::make($request->password);
            }
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
            $user_id = Tailor::find($request->id);

            $user_id->delete();
            return "কারিগর মুছে ফেলা হয়েছে।";
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }
}
