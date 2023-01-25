<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Company;
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
            $data = Employee::latest()->with('company')->get();
            return Datatables::of($data)
                ->editColumn('company', function ($data) {
                    return $data->company ? ucfirst($data->company->name) : "";
                })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                    // @can('list_company')
                    $btn = '';
                    if(auth()->user()->hasPermissionTo('edit_employee')) {

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                    }
                    if(auth()->user()->hasPermissionTo('delete_employee')) {
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                    }

                //    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                //    $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        $companies = Company::all();

        return view('employee.index',compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {

        Company::select($id->name)->get();
            return response()->json([
                'status' =>'200',
                'message' => 'Data Added sucessfully'
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'company_id'=>'required',
            'email'=> 'nullable|email',
            'phone' => 'nullable|numeric|digits:11'

        ],
        [
            'company_id.required' => 'Company field is required.'
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
            Employee::create($request->all());
            return response()->json([
                'status' =>'200',
                'message' => 'Data Added sucessfully'
            ]);
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
        $employee = Employee::find($id);
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
            'fname' => 'required',
            'lname' => 'required'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        try {
            $employee = Employee::find($id);
            $employee->update($request->all());
            return response()->json([
                'status' =>'200',
                'message' => 'Data Updated sucessfully'
            ]);
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
        Employee::find($id)->delete();

        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
