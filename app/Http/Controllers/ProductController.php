<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $product_code = $this->generateCode('Product', 'P');
        $products = Product::with('tailor', 'category')->latest()->get();

        return response()->json(["products" => $products, "product_code" => $product_code]);
    }

    public function create()
    {
        return view("pages.product.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'category_id'  => 'required',
            'tailor_price' => 'required',
            'retail_price' => 'required',
            'discount'     => 'required',
            'tailor_id'    => 'required',
            'image'        => 'nullable|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {

            $data               = new Product();
            $data->product_code = $request->product_code;
            $data->name         = $request->name;
            $data->description  = $request->description;
            $data->category_id  = $request->category_id;
            $data->tailor_price = $request->tailor_price;
            $data->retail_price = $request->retail_price;
            $data->discount     = $request->discount;
            $data->tailor_id    = $request->tailor_id;
            $data->user_id      = Auth::guard('web')->user()->id;
            if ($request->hasFile('image')) {
                $data->image      = $this->imageUpload($request, 'image', 'uploads/product');
            }
            $data->save();

            return response()->json(['status' => true, 'msg' => "পণ্য যুক্ত করা হয়েছে।"]);
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
            'name'         => 'required',
            'category_id'  => 'required',
            'tailor_price' => 'required',
            'retail_price' => 'required',
            'discount'     => 'required',
            'tailor_id'    => 'required',
            'image'        => 'nullable|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }

        try {

            $data               = Product::find($request->id);
            $data->name         = $request->name;
            $data->description  = $request->description;
            $data->category_id  = $request->category_id;
            $data->tailor_price = $request->tailor_price;
            $data->retail_price = $request->retail_price;
            $data->discount     = $request->discount;
            $data->tailor_id    = $request->tailor_id;
            $data->user_id      = Auth::guard('web')->user()->id;
            $data->updated_at   = Carbon::now();
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image      = $this->imageUpload($request, 'image', 'uploads/product');
            }
            $data->update();

            return response()->json(['status' => true, 'msg' => "পণ্য আপডেট করা হয়েছে।"]);
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
            $data = Product::find($request->id);
            $data->delete();
            return response()->json(['status' => true, 'msg' => "পণ্য মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}