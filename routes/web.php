<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    AdminAuthController,
    DashboardController,
    AdminController,
    CompanyController,
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
    CustomerController,
    ReminderController
};

/*
|--------------------------------------------------------------------------
| 1) Normal Misafir (Guest) Rotaları
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| 2) Normal Authed Kullanıcı Rotaları
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // CRM Kaynakları (customers ve users hariç)
    Route::resources([
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
        'reminders'       => ReminderController::class,
    ]);

    // Support – özel routelar
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/',             [SupportController::class, 'index']  )->name('index');
        Route::get('create',        [SupportController::class, 'create'] )->name('create');
        Route::get('pending',       [SupportController::class, 'pending'])->name('pending');
        Route::get('resolved',      [SupportController::class, 'resolved'])->name('resolved');
         Route::get('{support}',       [SupportController::class, 'show']    )->name('show');
        Route::post('/',            [SupportController::class, 'store']  )->name('store');
        Route::get('{support}/edit',[SupportController::class, 'edit']   )->name('edit');
        Route::put('{support}',     [SupportController::class, 'update'] )->name('update');
        Route::delete('{support}',  [SupportController::class, 'destroy'])->name('destroy');
    });

    // Reports – özel routelar
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                       [ReportController::class, 'index']                 )->name('index');
        Route::get('sales',                   [ReportController::class, 'sales']                 )->name('sales');
        Route::get('customers',               [ReportController::class, 'customers']             )->name('customers');
        Route::get('product-stock',           [ReportController::class, 'productStock']          )->name('product_stock');
        Route::get('current-account-summary', [ReportController::class, 'currentAccountSummary'])->name('account_summary');
        Route::get('support-request',         [ReportController::class, 'supportRequest']       )->name('support');
    });

    // Actions by Customer
   Route::get('actions/by-customer', [ActionController::class, 'byCustomer'])
     ->name('actions.by-customer');
});

/*
|--------------------------------------------------------------------------
| 3) Admin Panel Rotaları
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function(){

    // 3a) Admin misafir (giriş yapmamışsa)
    Route::middleware('admin.guest')->group(function(){
        Route::get('login',  [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    // 3b) Admin oturumlu
    Route::middleware('isAdmin')->group(function(){
        Route::post('logout',[AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/',      [AdminController::class,     'index'] )->name('dashboard');

        Route::resource('users',     UserController::class);
        Route::resource('customers', CustomerController::class);
    });

});
