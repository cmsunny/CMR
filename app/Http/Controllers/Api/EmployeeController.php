<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\EmployeeResource;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index(){
        // $employee = User::paginate(10);
        $paginate = apiPagination(User::paginate(10));
        $emplyees =  EmployeeResource::collection( $paginate);

        return response()->json([
            'data' => $emplyees,
            // 'pagination' => [
            //     'current_page' => $employee->currentPage(),
            //     'per_page' => $employee->perPage(),
            //     'total' => $employee->total()
            // ],
            'status' =>JsonResponse::HTTP_OK,
            'message' => 'Data Updated sucessfully'
        ], JsonResponse::HTTP_OK);
    }
}
