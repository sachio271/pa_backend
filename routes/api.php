<?php

use App\Http\Controllers\HRISApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get_subordinates/{ektp}', [HRISApiController::class, 'get_subordinates'])->name('get_subordinates');
Route::get('/get_all_company', [HRISApiController::class, 'get_all_company'])->name('get_all_company');
Route::get('/get_departments/{companyCode}', [HRISApiController::class, 'get_departments'])->name('get_departments');
Route::get('/get_departments/{companyCode}/{department}', [HRISApiController::class, 'get_employee_department'])->name('get_employee_department');
