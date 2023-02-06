<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\JsonResponse;
use DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::where('name', '!=', 'admin')->get();


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

        return view('roles.index');
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
            'name' => 'required|unique:roles',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>'402',
                'errors'=>$validator->messages(),
            ]);
        }

        try {
            $new_role = Role::create([
                'name' => $request->name,
                'title' => $request->title,
            ]);
            $new_role->permissions()->sync($request->permission);
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
        $role = Role::with('permissions')->get()->find($id);
        $permissions = Permission::all();
            // dd($role);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'data' => $role,
            'permissions'=> $permissions,
        ], JsonResponse::HTTP_OK);
    } catch (\Exception $e) {
        return response()->json([
            'status' => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $e->getMessage()
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

        try {
            $role = Role::find($id);

            $role->update($request->all());
            $role->syncPermissions($request->permission);
            return response()->json([
               'status' =>'200',
               'data' => $role,
            //    'permissions'=> $permissions,
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
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $role = Role::find($id);
            $role->syncPermissions();
            $role->delete();
        }catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
