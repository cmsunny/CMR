<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeApiController extends Controller
{
    public function index(){
        $employee = Employee::get()->paginate(10);
        dd($employee);
    }
}
