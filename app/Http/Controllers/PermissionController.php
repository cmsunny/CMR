<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::all();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-info active btn-sm editProduct">Edit</a>';
                $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-warning active btn-sm deleteProduct">Delete</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return response()->json([
          'status' => '200',
          'permissions' => $permissions
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
            'name' => 'required',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>'402',
                'errors'=>$validator->messages(),
            ]);
        }
        try {
             Permission::create([
                'name' =>  str_replace(' ', '_', $request->input('name')),
                'title' => $request->title,
            ]);

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
        $permission = Permission::findOrfail($id);
        return response()->json($permission);
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
            'name' => 'required|regex:/^\S*$/u',
        ], [
            'name.regex' => 'The :attribute field must not contain spaces.'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>'402',
                'errors'=>$validator->messages(),
            ]);
        }

        try {
            $permission = Permission::findOrfail($id);
            $permission->update($request->all());

            return response()->json([
               'status' =>JsonResponse::HTTP_OK,
               'data' => $permission,
               'message' => 'Data Added sucessfully'
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
        try{
            $role = Permission::findOrfail($id);
            $role->delete();
            return response()->json([
                'status' =>JsonResponse::HTTP_OK,
                'message' => 'Data Added sucessfully'
             ], JsonResponse::HTTP_OK);

        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
