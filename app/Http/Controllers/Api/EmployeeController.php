<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\EmployeeResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request){
        $keyword = request()->input('keyword');
        $employee = User::whereNotIn('first_name', ['admin','subadmin'])->where( function($query) use($keyword) {
            if(request()->input('keyword')) {
                $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', ["%{$keyword}%"])
                    ->orWhere('email', $keyword);
            }
        })->paginate(10);
        $paginate = apiPagination($employee, 10);
        $emplyees =  EmployeeResource::collection($employee);

        return response()->json([
            'data' => $emplyees,
            'meta' => $paginate,
            'status' =>JsonResponse::HTTP_OK,
            'message' => 'Data Updated sucessfully'
        ], JsonResponse::HTTP_OK);
    }
}
