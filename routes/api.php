<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee-lists');
    Route::post('/employee', [EmployeeController::class, 'store'])->name('employee-lists.store');
    Route::put('/employee/{id}', [EmployeeController::class, 'update'])->name('employee-lists.update');
    Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('employee-lists.show');
    Route::delete('/employee/{id}', [EmployeeController::class, 'destroy'])->name('employee-lists.destroy');

    Route::get('/role', [RoleController::class, 'index'])->name('role');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::get('/role/{id}', [RoleController::class, 'show'])->name('employee-lists.show');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('employee-lists.destroy');
});

Route::post('/register', [AdminController::class, 'store']);
Route::post('/login', [AdminController::class, 'login']); 




