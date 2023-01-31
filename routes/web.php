<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;

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
    // Route::get('company/restore/one/{id}', [CompanyController::class, 'restore'])->name('company.restore');
    // Route::get('company/restore_all', [CompanyController::class, 'restore_all'])->name('company.restore_all');
    // Route::delete('company/{id}/{das?}', [CompanyController::class, 'destroy'])->name('company.delete');
    // Route::get('company/{id}/{das?}', [CompanyController::class, 'destroy'])->name('company.delete');
    Route::resource('employee', EmployeeController::class);
    // Route::get('employee/restore/one/{id}', [EmployeeController::class, 'restore'])->name('employee.restore');
    // Route::get('employee/restore_all', [EmployeeController::class, 'restore_all'])->name('employee.restore_all');
    // Route::delete('employee/{id}/{das?}', [EmployeeController::class, 'destroy'])->name('employee.delete');
    // Route::get('employee/{id}/{das?}', [EmployeeController::class, 'destroy'])->name('employee.delete');

    Route::resource('role', RoleController::class);

});
require __DIR__.'/auth.php';
