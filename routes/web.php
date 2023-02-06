<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group([ 'middleware' => 'auth'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
Route::resource('company', CompanyController::class);
Route::resource('employee', EmployeeController::class);
Route::resource('role', RoleController::class);
Route::resource('permissions', PermissionsController::class);

});
require __DIR__.'/auth.php';
