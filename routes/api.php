<?php

use App\Http\Controllers\Authentication\RefreshAccessTokenEmployeeController;
use App\Http\Controllers\Authentication\RefreshAccessTokenAdminController;
use App\Http\Controllers\Authentication\ProfileEmployeeController;
use App\Http\Controllers\CashOnHand\CloseCashierInvoiceController;
use App\Http\Controllers\Authentication\LogoutEmployeeController;
use App\Http\Controllers\Transaction\TransactionDetailController;
use App\Http\Controllers\Dashboard\DashboardYearIncomeController;
use App\Http\Controllers\Authentication\LoginEmployeeController;
use App\Http\Controllers\Authentication\PasswordAdminController;
use App\Http\Controllers\Table\TableListWithConditionController;
use App\Http\Controllers\Authentication\ProfileAdminController;
use App\Http\Controllers\Transaction\TransactionListController;
use App\Http\Controllers\CashOnHand\CashierShiftListController;
use App\Http\Controllers\Authentication\LogoutAdminController;
use App\Http\Controllers\Dashboard\DashboardSummaryController;
use App\Http\Controllers\Report\ReportIncomeCompareController;
use App\Http\Controllers\Authentication\LoginAdminController;
use App\Http\Controllers\Order\OrderHistoryDetailController;
use App\Http\Controllers\CashOnHand\CloseCashierController;
use App\Http\Controllers\Dashboard\KitchenIncomeController;
use App\Http\Controllers\Category\CategoryCreateController;
use App\Http\Controllers\Category\CategoryDeleteController;
use App\Http\Controllers\Category\CategoryGetAllController;
use App\Http\Controllers\Category\CategoryGetOneController;
use App\Http\Controllers\Category\CategoryUpdateController;
use App\Http\Controllers\Employee\EmployeeCreateController;
use App\Http\Controllers\Employee\EmployeeDeleteController;
use App\Http\Controllers\Employee\EmployeeGetOneController;
use App\Http\Controllers\Employee\EmployeeUpdateController;
use App\Http\Controllers\Order\OrderTakeAwayListController;
use App\Http\Controllers\CashOnHand\OpenCashierController;
use App\Http\Controllers\Order\OrderHistoryListController;
use App\Http\Controllers\Table\TableOrderChangeController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Category\CategoryListController;
use App\Http\Controllers\Discount\DiscountListController;
use App\Http\Controllers\Employee\EmployeeListController;
use App\Http\Controllers\Kitchen\KitchenCreateController;
use App\Http\Controllers\Kitchen\KitchenDeleteController;
use App\Http\Controllers\Kitchen\KitchenGetAllController;
use App\Http\Controllers\Kitchen\KitchenGetOneController;
use App\Http\Controllers\Kitchen\KitchenUpdateController;
use App\Http\Controllers\Product\ProductCreateController;
use App\Http\Controllers\Product\ProductDeleteController;
use App\Http\Controllers\Product\ProductGetAllController;
use App\Http\Controllers\Product\ProductGetOneController;
use App\Http\Controllers\Product\ProductUpdateController;
use App\Http\Controllers\Printer\PrinterUpdateController;
use App\Http\Controllers\Report\ReportSummaryController;
use App\Http\Controllers\Kitchen\KitchenListController;
use App\Http\Controllers\Product\ProductListController;
use App\Http\Controllers\Packet\PacketCreateController;
use App\Http\Controllers\Packet\PacketDeleteController;
use App\Http\Controllers\Packet\PacketGetAllController;
use App\Http\Controllers\Packet\PacketGetOneController;
use App\Http\Controllers\Packet\PacketUpdateController;
use App\Http\Controllers\Report\ReportIncomeController;
use App\Http\Controllers\Order\OrderYearListController;
use App\Http\Controllers\Printer\PrinterGetController;
use App\Http\Controllers\Order\OrderPaymentController;
use App\Http\Controllers\Packet\PacketListController;
use App\Http\Controllers\Order\OrderCreateController;
use App\Http\Controllers\Order\OrderDetailController;
use App\Http\Controllers\Order\OrderUpdateController;
use App\Http\Controllers\Table\TableCreateController;
use App\Http\Controllers\Table\TableDeleteController;
use App\Http\Controllers\Table\TableGetOneController;
use App\Http\Controllers\Table\TableUpdateController;
use App\Http\Controllers\Table\TableListController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Tax\TaxGetController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function (): void {
	// Authentication
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

	// Cash On Hand
	Route::prefix('cashier')->middleware(['jwt', 'employee:cashier'])->group(function (): void {
		Route::post('open', [OpenCashierController::class, 'action']);
		Route::post('close', [CloseCashierController::class, 'action']);
		Route::get('close/invoice/{id}', [CloseCashierInvoiceController::class, 'action']);
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
				Route::put('edit/{id}', [PacketUpdateController::class, 'action']);
				Route::delete('delete/{id}', [PacketDeleteController::class, 'action']);
			});
		});

		// Waiter
		Route::prefix('waiter')->middleware(['jwt', 'employee:waiter'])->group(function (): void {
			Route::get('all', [ProductGetAllController::class, 'action']);
		});
	});

	// Packet
	Route::prefix('packet')->middleware(['jwt', 'employee:waiter'])->group(function (): void {
		Route::prefix('waiter')->group(function (): void {
			Route::get('all', [PacketGetAllController::class, 'action']);
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
		// Waiter
		Route::prefix('waiter')->middleware(['jwt', 'employee:waiter'])->group(function (): void {
			Route::get('all', [CategoryGetAllController::class, 'action']);
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
		// EMPLOYEE
		Route::prefix('employee')->middleware('api-employee')->group(function (): void {
			Route::get('list', [TableListWithConditionController::class, 'action']);
			Route::post('change', [TableOrderChangeController::class, 'action']);
		});
	});

	// Employee
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

	// Order
	Route::prefix('order')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::get('year/list', [OrderYearListController::class, 'action']);
		});
		// EMPLOYEE
		Route::prefix('employee')->middleware('api-employee')->group(function (): void {
			Route::get('history/list', [OrderHistoryListController::class, 'action']);
			Route::get('history/detail/{invoiceId}', [OrderHistoryDetailController::class, 'action']);
			Route::put('history/update/{invoiceId}', [OrderUpdateController::class, 'action']);
		});
		// CASHIER
		Route::prefix('cashier')->middleware(['jwt', 'employee:cashier'])->group(function (): void {
			Route::get('take-away/list', [OrderTakeAwayListController::class, 'action']);
			Route::get('detail/{invoiceId}', [OrderDetailController::class, 'action']);
			Route::post('payment/{invoiceId}', [OrderPaymentController::class, 'action']);
		});
		// WAITER
		Route::prefix('waiter')->middleware(['jwt', 'employee:waiter'])->group(function (): void {
			Route::post('create', [OrderCreateController::class, 'action']);
		});
	});

	// Transaction
	Route::prefix('transaction')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::get('list', [TransactionListController::class, 'action']);
			Route::get('detail/{invoiceId}', [TransactionDetailController::class, 'action']);
		});
	});

	// Dashboard
	Route::prefix('dashboard')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::get('year-income/get', [DashboardYearIncomeController::class, 'action']);
			Route::get('summary/get', [DashboardSummaryController::class, 'action']);
			Route::get('kitchen-income/get', [KitchenIncomeController::class, 'action']);
			Route::get('transaction/get', [TransactionController::class, 'action']);
		});
	});

	// Report
	Route::prefix('report')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::get('incomeCompare/get', [ReportIncomeCompareController::class, 'action']);
			Route::get('income/get', [ReportIncomeController::class, 'action']);
			Route::get('summary/get', [ReportSummaryController::class, 'action']);
			Route::get('get', [ReportController::class, 'action']);
		});
	});

	// Cashier Shift
	Route::prefix('cashier-shift')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::get('list', [CashierShiftListController::class, 'action']);
			Route::get('detail/{id}', [CloseCashierInvoiceController::class, 'action']);
		});
	});

	// Tax
	Route::prefix('tax')->group(function (): void {
		Route::get('get', [TaxGetController::class, 'action'])->middleware('jwt');
	});

	// Discount
	Route::prefix('discount')->group(function (): void {
		Route::get('list', [DiscountListController::class, 'action'])->middleware('jwt');
	});

	// Printer
	Route::prefix('printer')->group(function (): void {
		// ADMIN
		Route::prefix('admin')->middleware('api-admin')->group(function (): void {
			Route::get('get', [PrinterGetController::class, 'action']);
			Route::put('update', [PrinterUpdateController::class, 'action']);
		});
	});
});