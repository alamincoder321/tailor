<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use Authenticatable;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "username" => "required",
                "password" => "required"
            ]);
            if ($validator->fails()) {
                return response()->json(["error" => $validator->errors()]);
            }

            if (Auth::attempt($this->credentials($request->username, $request->password))) {
                return response()->json(["msg" => "Login successfully", "user_id" => Auth::user()->id]);
            } else {
                return response()->json(["unauthenticate" => "Username Or Password not match"]);
            }
        } catch (\Throwable $e) {
            return "Opps! Something went wrong";
        }
    }

    //credentials check
    public function credentials($username, $password)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $username, 'password' => $password];
        } else {
            return ['username' => $username, 'password' => $password];
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect("/");
    }
}
