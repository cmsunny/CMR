<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     
        if ($request->ajax()) {
            $data = Company::latest()->get();
  
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                   $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';

                   $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                    return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        
        return view('companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required',
            'email' => 'required|email|unique:companies',
            'website' => 'required|url'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>402,
                'errors'=>$validator->messages(),
            ]);
        } 
        Company::create($request->all());
         return response()->json([
            'status' =>'200',
            'message' => 'Data Added sucessfully'
         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        return response()->json($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'website' => 'required|url'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        } 

        $company = Company::find($id);
        $company->update($request->all());
        return response()->json([
            'status' =>'200',
            'message' => 'Data Added sucessfully'
        ]);       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Company::find($id)->delete();
     
        return response()->json(['success'=>'Company deleted successfully.']);
    }
}
