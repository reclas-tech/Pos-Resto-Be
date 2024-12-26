<?php

use App\Http\Controllers\Authentication\LoginEmployeeController;
use App\Http\Controllers\Authentication\LogoutAdminController;
use App\Http\Controllers\Authentication\LoginAdminController;
use App\Http\Controllers\Example\ExampleCreateController;
use App\Http\Controllers\Example\ExampleDeleteController;
use App\Http\Controllers\Example\ExampleGetAllController;
use App\Http\Controllers\Example\ExampleGetOneController;
use App\Http\Controllers\Example\ExampleUpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix('example')->group(function (): void {
	Route::post('create', [ExampleCreateController::class, 'action']);
	Route::get('list', [ExampleGetAllController::class, 'action']);
	Route::get('one/{id}', [ExampleGetOneController::class, 'action']);
	Route::put('update/{id}', [ExampleUpdateController::class, 'action']);
	Route::delete('delete/{id}', [ExampleDeleteController::class, 'action']);
});

Route::prefix('v1')->group(function (): void {
	Route::prefix('auth')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->group(function (): void {
			Route::post('login', [LoginAdminController::class, 'action']);
			Route::get('logout', [LogoutAdminController::class, 'action'])->middleware('api-admin');
		});
		// EMPLOYEE
		Route::prefix('employee')->group(function (): void {
			Route::post('login', [LoginEmployeeController::class, 'action']);
		});
	});
});
