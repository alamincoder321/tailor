<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $expense_code = $this->generateCode('Expense', 'EX');
        $expenses = Expense::latest()->get();

        return response()->json(["expenses" => $expenses, "expense_code" => $expense_code]);
    }

    public function create()
    {
        $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
            ->pluck('permissions')
            ->toArray();
        if (!in_array("expenseEntry", $access)) {
            return view("pages.unauthorize");
        }
        
        return view("pages.expense.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data               = new Expense();
            $data->expense_code = $request->expense_code;
            $data->date         = $request->date;
            $data->description  = $request->description;
            $data->amount       = $request->amount;
            $data->created_at   = Carbon::now();
            $data->save();
            return response()->json(['status' => true, 'msg' => "খরচ যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'amount' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data               = Expense::find($request->id);
            $data->expense_code = $request->expense_code;
            $data->date         = $request->date;
            $data->description  = $request->description;
            $data->amount       = $request->amount;
            $data->updated_at   = Carbon::now();
            $data->update();
            return response()->json(['status' => true, 'msg' => "খরচ আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Expense::where('id', $request->id)->first();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "খরচ মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
