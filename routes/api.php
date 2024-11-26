<?php

use App\Http\Controllers\HRISApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get_subordinates/{ektp}/{limit_date}', [HRISApiController::class, 'get_subordinates'])->name('get_subordinates');
Route::get('/get_nama_atasan/{ektp}/{limit_date}', [HRISApiController::class, 'get_nama_atasan'])->name('get_nama_atasan');
Route::post('/post_nama_atasan', [HRISApiController::class, 'post_nama_atasan'])->name('post_nama_atasan');
Route::get('/get_all_data_employees/{limit_date}', [HRISApiController::class, 'get_all_data_employees'])->name('get_all_data_employees');
Route::get('/get_all_company', [HRISApiController::class, 'get_all_company'])->name('get_all_company');
Route::get('/get_departments/{companyCode}', [HRISApiController::class, 'get_departments'])->name('get_departments');
Route::get('/get_employee_department/{companyCode}/{department}', [HRISApiController::class, 'get_employee_department'])->name('get_employee_department');
Route::get('/get_users', [HRISApiController::class, 'get_users'])->name('get_users');
Route::get('/get_users_specified/{company}/{department}', [HRISApiController::class, 'get_users_specified'])->name('get_users_specified');
