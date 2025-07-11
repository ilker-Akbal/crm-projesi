<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CompanyController, CustomerController, ContactController, OrderController,
    OfferController, ProductController, ProductStockController, ProductPriceController,
    AccountController, MovementController, ReportController, SupportController,
    ActionController, UserController, ReminderController
};

/* -------------------------------------------------
 |  Dashboard (Anasayfa)
 |-------------------------------------------------*/
Route::view('/', 'dashboard')->name('dashboard.index');

/* -------------------------------------------------
 |  ÖZEL ROUTE’LAR  (resource’lardan ÖNCE!)
 |-------------------------------------------------*/

/* Support */
Route::get('support/pending',   [SupportController::class, 'pending' ])->name('support.pending');
Route::get('support/resolved',  [SupportController::class, 'resolved'])->name('support.resolved');

/* Reports */
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('sales',                   [ReportController::class, 'sales'               ])->name('sales');
    Route::get('customers',               [ReportController::class, 'customers'           ])->name('customers');
    Route::get('product_stock',           [ReportController::class, 'productStock'        ])->name('product_stock');
    Route::get('current_account_summary', [ReportController::class, 'currentAccountSummary'])->name('account_summary');
    Route::get('support_request',         [ReportController::class, 'supportRequest'      ])->name('support');
});

/* Actions */
Route::get('actions/by-customer', [ActionController::class, 'byCustomer'])->name('actions.by-customer');

/* Customers & Users ekstra sayfalar */
Route::get('customers/details', [CustomerController::class, 'details'])->name('customers.details');
Route::get('users/roles',       [UserController::class,    'roles'  ])->name('users.roles');

/* -------------------------------------------------
 |  RESOURCE ROUTE’LAR
 |-------------------------------------------------*/
Route::resources([
    'customers'       => CustomerController::class,
    'companies'       => CompanyController::class,
    'contacts'        => ContactController::class,
    'orders'          => OrderController::class,
    'offers'          => OfferController::class,
    'products'        => ProductController::class,
    'product_stocks'  => ProductStockController::class,
    'product_prices'  => ProductPriceController::class,
    'accounts'        => AccountController::class,
    'movements'       => MovementController::class,
    'reports'         => ReportController::class,   // buradaki “show” rotası artık /reports/{id}
    'support'         => SupportController::class,
    'actions'         => ActionController::class,
    'users'           => UserController::class,
    'reminders'       => ReminderController::class,
]);
