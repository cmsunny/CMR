<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:list_company')->only('index');
        $this->middleware('can:create_company')->only(['create', 'store']);
        $this->middleware('can:edit_company')->only(['edit', 'update']);
        $this->middleware('can:delete_company')->only(['delete']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Company::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';

                    return $btn;
                })->addColumn('image', function ($row) {
                $src = $row->image ? asset('storage/' . $row->image) : asset('assets/images/placeholder.png');
                return '<img src=" '.$src.'" border="2" width="35px" height="50px" class="img-rounded" align="center" />';
            })
            ->rawColumns(['action','image'])
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
        $width=config('image_size.image.width');
        $height=config('image_size.image.height');
        $size=config('image_size.image.size');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:companies',
            'image' => "required|dimensions:width=$width,height:$height|max:$size",
            'website' => 'required|url'
        ],[
            'image.dimensions'=>'Image dimensions must be 100 x 200',
            'image.size'=>'Image Size must be 1024'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>402,
                'errors'=>$validator->messages(),
            ]);
        }
        try {
            $data = $request->except('image');
            if($request->hasFile('image')){
                $data['image'] = saveResizeImage($request->image, 'images/companies', 100);
            }
            Company::create($data);
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
        $company = Company::findOrfail($id);
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
            'email' => ['required', 'email',
                Rule::unique('companies')->ignore($id)
            ],
            'website' => 'required|url'
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>402,
                'errors'=>$validator->messages(),
            ]);
        }
        try {
            $data = $request->except(['_token','_method','image']);
            $company = Company::findOrfail($id);
            if($request->hasFile('image')){
                if(File::exists($company->image)) {
                    File::delete($company->image);
                }
                $data['image'] = saveResizeImage($request->image, 'images/companies', 550);
            }
            $company = Company::findOrfail($id);
            $company->update($data);
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
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            $company = Company::findOrfail($id);

            $company->name = Hash::make($company->name );
            $company->email = Hash::make($company->email );
            $company->website = Hash::make($company->website );
            $company->save();
            $company->delete();
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
