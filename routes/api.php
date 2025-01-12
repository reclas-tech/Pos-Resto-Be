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
use App\Http\Controllers\Employee\EmployeeCreateController;
use App\Http\Controllers\Employee\EmployeeDeleteController;
use App\Http\Controllers\Employee\EmployeeGetOneController;
use App\Http\Controllers\Employee\EmployeeUpdateController;
use App\Http\Controllers\Employee\EmployeeListController;
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
use App\Http\Controllers\Packet\PacketCreateController;
use App\Http\Controllers\Packet\PacketGetOneController;
use App\Http\Controllers\Packet\PacketListController;
use App\Http\Controllers\Product\ProductCreateController;
use App\Http\Controllers\Product\ProductDeleteController;
use App\Http\Controllers\Product\ProductGetOneController;
use App\Http\Controllers\Product\ProductListController;
use App\Http\Controllers\Product\ProductUpdateController;
use App\Http\Controllers\Table\TableCreateController;
use App\Http\Controllers\Table\TableDeleteController;
use App\Http\Controllers\Table\TableGetOneController;
use App\Http\Controllers\Table\TableListController;
use App\Http\Controllers\Table\TableUpdateController;
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
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::post('create', [ProductCreateController::class, 'action']);
			Route::get('list', [ProductListController::class, 'action']);
			Route::get('detail/{id}', [ProductGetOneController::class, 'action']);
			Route::put('edit/{id}', [ProductUpdateController::class, 'action']);
			Route::delete('delete/{id}', [ProductDeleteController::class, 'action']);

			// Packet Product
			Route::prefix('packet')->group(function (): void {
				Route::post('create', [PacketCreateController::class, 'action']);
				Route::get('list', [PacketListController::class, 'action']);
				Route::get('detail/{id}', [PacketGetOneController::class, 'action']);
			});
		});
	});

	// Kitchen
	Route::prefix('kitchen')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
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
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::post('create', [CategoryCreateController::class, 'action']);
			Route::get('list', [CategoryListController::class, 'action']);
			Route::get('detail/{id}', [CategoryGetOneController::class, 'action']);
			Route::put('edit/{id}', [CategoryUpdateController::class, 'action']);
			Route::delete('delete/{id}', [CategoryDeleteController::class, 'action']);
		});
	});

	// Table
	Route::prefix('table')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::post('create', [TableCreateController::class, 'action']);
			Route::get('list', [TableListController::class, 'action']);
			Route::get('detail/{id}', [TableGetOneController::class, 'action']);
			Route::put('edit/{id}', [TableUpdateController::class, 'action']);
			Route::delete('delete/{id}', [TableDeleteController::class, 'action']);
		});
	});

	//Employee
	Route::prefix('employee')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::post('create', [EmployeeCreateController::class, 'action']);
			Route::get('list', [EmployeeListController::class, 'action']);
			Route::get('detail/{id}', [EmployeeGetOneController::class, 'action']);
			Route::put('edit/{id}', [EmployeeUpdateController::class, 'action']);
			Route::delete('delete/{id}', [EmployeeDeleteController::class, 'action']);
		});
	});
});
