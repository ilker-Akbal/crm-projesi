<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CompanyController,
    CustomerController,
    ContactController,
    OrderController,
    OfferController,
    ProductController,
    ProductStockController,
    ProductPriceController,
    AccountController,
    MovementController,
    ReportController,
    SupportController,
    ActionController,
    UserController,
    ReminderController,
    DashboardController,
    AuthController
};

Route::middleware('guest')->group(function () {
    Route::get ('/login',    [AuthController::class, 'showLogin'   ])->name('login');
    Route::post('/login',    [AuthController::class, 'login'       ]);


});

/* ---------- Auth alanı ---------- */
Route::middleware('auth')->group(function () {

    /* Dashboard (index) */
    Route::get('/', [DashboardController::class, 'index'])
          ->name('dashboard.index');

    /* Logout */
    Route::post('/logout', [AuthController::class, 'logout'])
          ->name('logout');

    /* CRM kaynakları (orders, offers, …) */
    Route::resources([
        'customers'       => CustomerController::class,
        'companies'       => CompanyController::class,
        /* ... diğer resource rotalar ... */
    ]);

/*
|--------------------------------------------------------------------------
| Support – Özel Routelar
|--------------------------------------------------------------------------
*/
Route::prefix('support')->name('support.')->group(function () {
    Route::get('/',               [SupportController::class, 'index'   ])->name('index');
    Route::get('create',          [SupportController::class, 'create'  ])->name('create');
    Route::get('pending',         [SupportController::class, 'pending' ])->name('pending');
    Route::get('resolved',        [SupportController::class, 'resolved'])->name('resolved');
    Route::post('/',              [SupportController::class, 'store'   ])->name('store');
    Route::get('{support}',       [SupportController::class, 'show'    ])->name('show');
    Route::get('{support}/edit',  [SupportController::class, 'edit'    ])->name('edit');
    Route::put('{support}',       [SupportController::class, 'update'  ])->name('update');
    Route::delete('{support}',    [SupportController::class, 'destroy' ])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Reports – Özel Routelar
|--------------------------------------------------------------------------
*/
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/',                     [ReportController::class, 'index'                  ])->name('index');
    Route::get('sales',                 [ReportController::class, 'sales'                  ])->name('sales');
    Route::get('customers',             [ReportController::class, 'customers'              ])->name('customers');
    Route::get('product-stock',         [ReportController::class, 'productStock'           ])->name('product_stock');
    Route::get('current-account-summary',[ReportController::class, 'currentAccountSummary'])->name('account_summary');
    Route::get('support-request',       [ReportController::class, 'supportRequest'        ])->name('support');
});

/*
|--------------------------------------------------------------------------
| Actions – Özel Route
|--------------------------------------------------------------------------
*/
Route::get('actions/by-customer', [ActionController::class, 'byCustomer'])->name('actions.by-customer');

/*
|--------------------------------------------------------------------------
| Users – Ekstra Sayfa
|--------------------------------------------------------------------------
*/
Route::get('users/roles', [UserController::class, 'roles'])->name('users.roles');

/*
|--------------------------------------------------------------------------
| Resource Routelar
|--------------------------------------------------------------------------
*/
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
    'actions'         => ActionController::class,
    'users'           => UserController::class,
    'reminders'       => ReminderController::class,
    // support ve reports resource'ları zaten yukarıda ayrı tanımlı
]);
});