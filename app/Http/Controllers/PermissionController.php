<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
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
        // dd($request->all());
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
        // $request->merge(['name' => str_replace(' ', '_', $request->input('name'))]);
        try {
             Permission::create([
                'name' =>  str_replace(' ', '_', $request->input('name')),
                'title' => $request->title,
            ]);
            // dd($new_role);
            // $new_role->permissions()->sync($request->permission);
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
        $permission = Permission::find($id);
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


        // dd($request->all());
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
            $permission = Permission::find($id);

            $permission->update($request->all());
            // $role->syncPermissions($request->permission);
            return response()->json([
               'status' =>'200',
               'data' => $permission,
               'message' => 'Data Added sucessfully'
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
        try{
            $role = Permission::find($id);
            // $role->syncPermissions();
            $role->delete();
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
