<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReminderController;

// Dashboard (Anasayfa)
Route::view('/', 'dashboard')->name('dashboard.index');

// Resource Route'lar
Route::resource('customers', CustomerController::class);
Route::resource('companies', CompanyController::class);
Route::resource('contacts', ContactController::class);
Route::resource('orders', OrderController::class);
Route::resource('offers', OfferController::class);
Route::resource('products', ProductController::class);
Route::resource('product_stocks', ProductStockController::class);
Route::resource('product_prices', ProductPriceController::class);
Route::resource('accounts', AccountController::class);
Route::resource('movements', MovementController::class);
Route::resource('reports', ReportController::class);
Route::resource('support', SupportController::class);
Route::resource('actions', ActionController::class);
Route::resource('users', UserController::class);
Route::resource('reminders', ReminderController::class);

// === Özel Route'lar ===

// Support özel route'ları
Route::get('support/pending', [SupportController::class, 'pending'])->name('support.pending');
Route::get('support/resolved', [SupportController::class, 'resolved'])->name('support.resolved');

// Reports özel route'ları
Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
Route::get('reports/product_stock', [ReportController::class, 'productStock'])->name('reports.product_stock');
Route::get('reports/current_account_summary', [ReportController::class, 'currentAccountSummary'])->name('reports.account_summary');
Route::get('reports/support-request', [ReportController::class, 'supportRequest'])->name('reports.support');

// Actions özel route
Route::get('actions/by-customer', [ActionController::class, 'byCustomer'])->name('actions.by-customer');

// Customers detay sayfası (şimdilik parametresiz, daha sonra ID ile yapılabilir)
Route::get('customers/details', [CustomerController::class, 'details'])->name('customers.details');

// Users -> Roles sayfası (şimdilik boş sayfa olabilir)
Route::get('users/roles', [UserController::class, 'roles'])->name('users.roles');
