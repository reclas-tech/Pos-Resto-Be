<?php

use App\Http\Controllers\Authentication\RefreshAccessTokenAdminController;
use App\Http\Controllers\Authentication\ProfileEmployeeController;
use App\Http\Controllers\Authentication\LogoutEmployeeController;
use App\Http\Controllers\Authentication\LoginEmployeeController;
use App\Http\Controllers\Authentication\PasswordAdminController;
use App\Http\Controllers\Authentication\ProfileAdminController;
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
			Route::post('forget-password', [PasswordAdminController::class, 'forgetPassword']);
			Route::post('otp-verification', [PasswordAdminController::class, 'otpVerification'])->middleware('jwt');
			Route::post('new-password', [PasswordAdminController::class, 'changePassword'])->middleware('jwt');
			Route::get('profile', [ProfileAdminController::class, 'action'])->middleware('api-admin');
			Route::get('refresh-access-token', [RefreshAccessTokenAdminController::class, 'action'])->middleware('jwt');
		});
		// EMPLOYEE
		Route::prefix('employee')->group(function (): void {
			Route::post('login', [LoginEmployeeController::class, 'action']);
			Route::get('logout', [LogoutEmployeeController::class, 'action'])->middleware('api-employee');
			Route::get('profile', [ProfileEmployeeController::class, 'action'])->middleware('api-employee');
		});
	});
});
