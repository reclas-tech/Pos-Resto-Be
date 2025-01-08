<?php

use App\Http\Controllers\Authentication\RefreshAccessTokenEmployeeController;
use App\Http\Controllers\Authentication\RefreshAccessTokenAdminController;
use App\Http\Controllers\Authentication\ProfileEmployeeController;
use App\Http\Controllers\Authentication\LogoutEmployeeController;
use App\Http\Controllers\Authentication\LoginEmployeeController;
use App\Http\Controllers\Authentication\PasswordAdminController;
use App\Http\Controllers\Authentication\ProfileAdminController;
use App\Http\Controllers\Authentication\LogoutAdminController;
use App\Http\Controllers\Authentication\LoginAdminController;
use App\Http\Controllers\Category\CategoryCreateController;
use App\Http\Controllers\Category\CategoryDeleteController;
use App\Http\Controllers\Category\CategoryGetOneController;
use App\Http\Controllers\Category\CategoryListController;
use App\Http\Controllers\Category\CategoryUpdateController;
use App\Http\Controllers\Example\ExampleCreateController;
use App\Http\Controllers\Example\ExampleDeleteController;
use App\Http\Controllers\Example\ExampleGetAllController;
use App\Http\Controllers\Example\ExampleGetOneController;
use App\Http\Controllers\Example\ExampleUpdateController;
use App\Http\Controllers\Kitchen\KitchenCreateController;
use App\Http\Controllers\Kitchen\KitchenDeleteController;
use App\Http\Controllers\Kitchen\KitchenGetAllController;
use App\Http\Controllers\Kitchen\KitchenGetOneController;
use App\Http\Controllers\Kitchen\KitchenListController;
use App\Http\Controllers\Kitchen\KitchenUpdateController;
use App\Http\Controllers\Product\ProductCreateController;
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
			Route::get('refresh-access-token', [RefreshAccessTokenEmployeeController::class, 'action'])->middleware('jwt');
		});
	});

	// Product
	Route::prefix('product')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->group(function (): void {
			Route::post('create', [ProductCreateController::class, 'action']);
		});
	});

	// Kitchen
	Route::prefix('kitchen')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->group(function (): void {
			Route::post('create', [KitchenCreateController::class, 'action']);
			Route::get('list', [KitchenListController::class, 'action']);
			Route::get('get', [KitchenGetAllController::class, 'action']);
			Route::get('detail/{id}', [KitchenGetOneController::class, 'action']);
			Route::put('edit/{id}', [KitchenUpdateController::class, 'action']);
			Route::delete('delete/{id}', [KitchenDeleteController::class, 'action']);
		});
	});

	// Category
	Route::prefix('category')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->group(function (): void {
			Route::post('create', [CategoryCreateController::class, 'action']);
			Route::get('list', [CategoryListController::class, 'action']);
			Route::get('detail/{id}', [CategoryGetOneController::class, 'action']);
			Route::put('edit/{id}', [CategoryUpdateController::class, 'action']);
			Route::delete('delete/{id}', [CategoryDeleteController::class, 'action']);
		});
	});
});
