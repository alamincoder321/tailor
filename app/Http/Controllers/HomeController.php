<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Tailor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getProfit()
    {
        $month = date("m");
        $year = date("Y");
        $today = date('Y-m-d');
        $dayNumber = date('t', mktime(0, 0, 0, $month, 1, $year));

        $clauses = "";
        // $areaId = Auth::guard('admin')->user();
        // if ($areaId->role == 'manager') {
        //     $clauses .= "AND c.thana_id = '$areaId->thana_id'";
        // }

        $todayOrder = DB::select("SELECT sm.*
        FROM orders sm
        LEFT JOIN customers c ON c.id = sm.customer_id
        WHERE sm.orderDate = '$today' AND sm.status = 'pending'
        $clauses
        ");
        $pendingOrder = DB::select("SELECT sm.*
        FROM orders sm
        LEFT JOIN customers c ON c.id = sm.customer_id
        WHERE sm.status = 'pending'
        $clauses
        ");
        $yearOrder = DB::select("SELECT sm.*
        FROM orders sm
        LEFT JOIN customers c ON c.id = sm.customer_id
        WHERE YEAR(sm.orderDate) = '$year' AND sm.status = 'pending'
        $clauses
        ");
        $complete = DB::select("SELECT sm.*
            FROM orders sm
            LEFT JOIN customers c ON c.id = sm.customer_id
            WHERE sm.status = 'complete'
            $clauses
        ");
        $orderDetail = DB::select("SELECT
                    o.*
                FROM orders o
                LEFT JOIN customers c ON c.id = o.customer_id
                WHERE o.status != 'cancel'
            $clauses
        ");

        // monthly record
        $monthlyRecord = [];
        for ($i = 1; $i <= $dayNumber; $i++) {
            $date = $year . '-' . $month . '-' . sprintf("%02d", $i);
            $query = DB::select("SELECT 
            IFNULL(SUM(sm.total), 0 ) AS sales_amount
            FROM orders sm
            WHERE sm.orderDate = ?
            AND sm.status = 'delivery'", [$date]);

            $amount = (float)$query[0]->sales_amount;

            $sale = [sprintf("%02d", $i), $amount];
            array_push($monthlyRecord, $sale);
        }

        // yearly record
        $yearlyRecord = [];
        for ($i = 1; $i <= 12; $i++) {
            $yearMonth = $year . sprintf("%02d", $i);
            $query = DB::select("SELECT 
                    IFNULL(SUM(sm.total), 0 ) AS sales_amount
                    FROM orders sm
                    WHERE extract(year_month from sm.orderDate) = ?
                    AND sm.status = 'delivery'", [$yearMonth]);


            $amount = (float)$query[0]->sales_amount;
            $monthName = date("M", mktime(0, 0, 0, $i, 10));

            $sale = [$monthName, $amount];
            array_push($yearlyRecord, $sale);
        }

        // top sold service
        // $topSold = DB::select("SELECT
        //                 s.name AS service_name,
        //                 SUM(od.quantity) as qty
        //             FROM order_items od
        //             JOIN services s ON s.id = od.service_id
        //             JOIN orders o ON o.id = od.order_id
        //             WHERE o.status != 'cancel' AND o.status != 'pending'
        //             GROUP BY service_name LIMIT 5");
        // top sold product
        // $topCustomer = DB::select("SELECT
        //                     c.name,
        //                     ifnull(SUM(o.total), 0) as total_amount
        //                     FROM orders o
        //                     JOIN users c ON c.id = o.customer_id
        //                     WHERE o.status != 'pending' 
        //                     AND o.status != 'cancel'
        //                     GROUP BY name LIMIT 5");


        return response()->json([
            'today_order'   => $todayOrder,
            'pending_order' => $pendingOrder,
            'year_order'    => $yearOrder,
            'completed'     => $complete,
            'order_detail'  => $orderDetail,
            'tailor'        => Tailor::get(),
            'customer'      => Customer::get(),
        ]);
    }
}
