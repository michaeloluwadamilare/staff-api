<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employee', [EmployeeController::class, 'index']);
    Route::post('/employee', [EmployeeController::class, 'store']);
    Route::put('/employee/{id}', [EmployeeController::class, 'update']);
    Route::get('/employee/{id}', [EmployeeController::class, 'show']);
    Route::delete('/employee/{id}', [EmployeeController::class, 'destroy']);

    Route::get('/role', [RoleController::class, 'index']);
    Route::post('/role', [RoleController::class, 'store']);
    Route::put('/role/{id}', [RoleController::class, 'update']);
    Route::get('/role/{id}', [RoleController::class, 'show']);
    Route::delete('/role/{id}', [RoleController::class, 'destroy']);
});

Route::post('/register', [AdminController::class, 'store']);
Route::post('/login', [AdminController::class, 'login']); 




