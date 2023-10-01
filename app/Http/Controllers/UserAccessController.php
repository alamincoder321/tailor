<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Permission;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        if (Auth::guard('web')->user()->role->name != 'SuperAdmin') {
            $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
                ->pluck('permissions')
                ->toArray();
            if (!in_array("userEntry", $access)) {
                return view("pages.unauthorize");
            }
        }

        return view("pages.user.create");
    }

    public function index()
    {
        if (Auth::user()->role->name == 'SuperAdmin') {
            $data = User::where([["id", "!=", 1], ["id", "!=", Auth::user()->id]])->with('role')->latest()->get();
        } else {
            $data = User::where("id", "!=", 1)->with('role')->latest()->get();
        }
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
            $data = new User();
            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->role_id  = $request->role_id;
            $data->password = Hash::make($request->password);
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image      = $this->imageUpload($request, 'image', 'uploads/user');
            }
            $data->created_at = Carbon::now();
            $data->save();

            return response()->json(['status' => true, 'msg' => "ইউজার যুক্ত করা হয়েছে।"]);
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
            $data = User::find($request->id);

            $data->name     = $request->name;
            $data->username = $request->username;
            $data->email    = $request->email;
            $data->role_id     = $request->role_id;
            if ($request->role_id == 1) {
                UserAccess::where('user_id', $request->id)->delete();
            }

            if (!empty($request->password)) {
                $data->password = Hash::make($request->password);
            }
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image      = $this->imageUpload($request, 'image', 'uploads/user');
            }

            $data->update();
            return response()->json(['status' => true, 'msg' => "ইউজার আপডেট করা হয়েছে।"]);
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
            $user_id = User::find($request->id);

            $user_id->delete();
            return "User Delete successfully";
        } catch (\Throwable $e) {
            return "Opps! something went wrong";
        }
    }

    // permission edit
    public function permissionEdit($id)
    {
        $user = User::find($id);

        if (Auth::guard('web')->user()->role->name != 'SuperAdmin') {
            if (empty($user)) {
                return back();
            } else if ($user->id == 1) {
                return back();
            }

            $accesss = UserAccess::where('user_id', Auth::guard('web')->user()->id)
                ->pluck('permissions')
                ->toArray();
            if (!in_array("userAccess", $accesss)) {
                return view("pages.unauthorize");
            }
        }

        $userAccess = UserAccess::where('user_id', $id)->pluck('permissions')->toArray();
        $access = UserAccess::where('user_id', $id)->get();
        $groups = Permission::groupBy('group_name')->orderBy('id', 'asc')->get();
        foreach ($groups as $key => $item) {
            $item->permissionArr = Permission::where('group_name', $item->group_name)->get();
        }

        return view('pages.user.useraccess', compact('user', 'access', 'userAccess', 'groups'));
    }

    public function permissionStore(Request $request)
    {
        try {
            $user = User::with('role')->find($request->user_id);
            if ($user->role->name == 'SuperAdmin') {
                return redirect('/user')->with('error', 'This user is Super Admin');
            }

            UserAccess::where('user_id', $request->user_id)->delete();
            if (empty($request->permissions)) {
                return redirect('/user')->with('error', 'Permission all remove');
            }

            $permissions = Permission::all();
            foreach ($permissions as $value) {
                if (in_array($value->id, $request->permissions)) {
                    UserAccess::create([
                        'user_id'    => $request->user_id,
                        'group_name'  => $value->group_name,
                        'permissions' => $value->permission,
                    ]);
                }
            }

            return redirect('/user')->with('success', 'Permissions added successfullly');
        } catch (\Throwable $e) {
            return redirect('/user');
        }
    }
}
