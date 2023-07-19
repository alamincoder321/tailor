<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $order_code = $this->generateCode("Order", '#O');
        return response()->json(['orderCode' => $order_code]);
    }

    public function create()
    {
        return view("pages.order.create");
    }
}
