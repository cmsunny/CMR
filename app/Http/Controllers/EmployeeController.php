<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:list_employee')->only('index');
        $this->middleware('can:create_employee')->only(['create', 'store']);
        $this->middleware('can:edit_employee')->only(['edit', 'update']);
        $this->middleware('can:delete_employee')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = User::whereNotIn('first_name', ['admin','subadmin'])->with(['company'],['roles'])->latest()->get();

            return Datatables::of($data)
                ->editColumn('company', function ($data) {
                    return $data->company ? ucfirst($data->company->name) : "";
                })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                    $btn = '';
                    if(auth()->user()->hasPermissionTo('edit_employee')) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                    }
                    if(auth()->user()->hasPermissionTo('delete_employee')) {
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                    }
            return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        $companies = Company::all();
        $roles = Role::whereNotIn('name', ['admin','subadmin'])->get();

        return view('employee.index',compact('companies', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_id' => 'required',
            'role_id' => 'required',
            'email'=> 'email|unique:users',
            'phone' => 'bail|numeric|digits:11|unique:users',
            'password' => ['bail', 'required', 'min:8']

        ],[
            'company_id.required' => 'Company Feild is Required !',
            'role_id.required' => 'Role Feild is Required !'
        ]
    );
        if($validator->fails())
        {
            return response()->json([
                'status'=>402,
                'errors'=>$validator->messages(),
            ]);
        }
        try {

            $password = Hash::make($request->password);
            $request->merge(['password' => $password]);
            $user = User::create($request->all());
            $user->roles()->sync($request->role_id);
            return response()->json([
                'status' =>JsonResponse::HTTP_OK,
                'message' => 'Data Added sucessfully'
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = User::with(['company', 'roles'])->findOrfail($id);
        return response()->json($employee);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=> 'email|unique:employee',
            'phone' => 'bail|numeric|digits:11|unique:employee'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        try {
            $password = Hash::make($request->password);
            $request->merge(['password' => $password]);
            $employee = User::findOrfail($id);
            $employee->update($request->all());
            $employee->roles()->sync($request->role_id);
            return response()->json([
                'status' =>JsonResponse::HTTP_OK,
                'message' => 'Data Updated sucessfully'
            ], JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $employee = User::find($id);
            $employee->first_name = Hash::make($employee->fname);
            $employee->last_name = Hash::make($employee->lname);
            $employee->phone = Hash::make($employee->phone);
            $employee->email = Hash::make($employee->email);
            $employee->save();
            $employee->delete();

            return response()->json([
                'status' => JsonResponse::HTTP_OK,
                'message' => 'Data Added sucessfully'
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
