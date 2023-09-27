<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->id != '') {
            $data = Employee::find($request->id);
        } else {
            $data = Employee::latest()->get();
        }
        return $data;
    }

    public function manage()
    {
        $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
            ->pluck('permissions')
            ->toArray();
        if (!in_array("employeeList", $access)) {
            return view("pages.unauthorize");
        }
        return view("pages.employee.manage");
    }

    public function create($id = '')
    {
        $access = UserAccess::where('user_id', Auth::guard('web')->user()->id)
            ->pluck('permissions')
            ->toArray();
        if (!in_array("employeeEntry", $access)) {
            return view("pages.unauthorize");
        }

        return view("pages.employee.create", compact('id'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'mobile'            => 'required',
            'present_address'   => 'required',
            'permanent_address' => 'required',
            'father_name'       => 'required',
            'gender'            => 'required',
            'designation_id'    => 'required',
            'salary_range'      => 'required',
            'birth_date'               => 'required',
            'join_date'         => 'required',
            'email'             => 'nullable|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data                    = new Employee();
            $data->name              = $request->name;
            $data->email             = $request->email;
            $data->mobile            = $request->mobile;
            $data->gender            = $request->gender;
            $data->designation_id    = $request->designation_id;
            $data->birth_date        = $request->birth_date;
            $data->father_name       = $request->father_name;
            $data->mother_name       = $request->mother_name;
            $data->present_address   = $request->present_address;
            $data->permanent_address = $request->permanent_address;
            $data->join_date         = $request->join_date;
            $data->salary_range      = $request->salary_range;
            $data->addedBy           = Auth::guard('web')->user()->id;
            $data->created_at        = Carbon::now();
            if ($request->hasFile('image')) {
                $data->image = $this->imageUpload($request, 'image', 'uploads/employee');
            }
            $data->save();
            return response()->json(['status' => true, 'msg' => "এমপ্লয়ী যুক্ত করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required',
            'mobile'            => 'required',
            'present_address'   => 'required',
            'permanent_address' => 'required',
            'father_name'       => 'required',
            'gender'            => 'required',
            'designation_id'    => 'required',
            'salary_range'      => 'required',
            'birth_date'               => 'required',
            'join_date'         => 'required',
            'email'             => 'nullable|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'msg' => 'validation error', 'errors' => $validator->errors()]);
        }
        try {
            $data                    = Employee::find($request->id);
            $data->name              = $request->name;
            $data->email             = $request->email;
            $data->mobile            = $request->mobile;
            $data->gender            = $request->gender;
            $data->designation_id    = $request->designation_id;
            $data->birth_date        = $request->birth_date;
            $data->father_name       = $request->father_name;
            $data->mother_name       = $request->mother_name;
            $data->present_address   = $request->present_address;
            $data->permanent_address = $request->permanent_address;
            $data->join_date         = $request->join_date;
            $data->salary_range      = $request->salary_range;
            $data->addedBy           = Auth::guard('web')->user()->id;
            $data->updated_at        = Carbon::now();
            if ($request->hasFile('image')) {
                if (File::exists($data->image)) {
                    File::delete($data->image);
                }
                $data->image = $this->imageUpload($request, 'image', 'uploads/employee');
            }
            $data->update();
            return response()->json(['status' => true, 'msg' => "এমপ্লয়ী আপডেট করা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = Employee::where('id', $request->id)->first();
            $data->delete();
            return response()->json(['status' => true, 'msg' => "এমপ্লয়ী মুছে ফেলা হয়েছে।"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'msg' => $th->getMessage()]);
        }
    }
}
