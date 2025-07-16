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
    ReminderController,
    
};

/*
|--------------------------------------------------------------------------
| Misafir (Guest) Rotaları
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Normal kullanıcı login
    Route::get('/login',  [AuthController::class,'showLogin'])->name('login');
    Route::post('/login', [AuthController::class,'login']);
});

/*
|--------------------------------------------------------------------------
| Admin Misafir (Guest) Rotaları
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('admin.guest')->group(function() {
    // Admin login form & işlemi
    Route::get ('/login',  [AdminAuthController::class,'showLogin'])->name('login');
    Route::post('/login',  [AdminAuthController::class,'login']);
});

/*
|--------------------------------------------------------------------------
| Auth’li Normal Kullanıcı Rotaları
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Normal kullanıcı logout
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');

    // CRM Dashboard (manager/user)
    Route::get('/', [DashboardController::class,'index'])
         ->name('dashboard.index');

    // CRM kaynakları (users/customers hariç)
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

    // Support
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/',            [SupportController::class,'index']  )->name('index');
        Route::get('create',       [SupportController::class,'create'] )->name('create');
        Route::get('pending',      [SupportController::class,'pending'])->name('pending');
        Route::get('resolved',     [SupportController::class,'resolved'])->name('resolved');
        Route::post('/',           [SupportController::class,'store']  )->name('store');
        Route::get('{support}/edit',[SupportController::class,'edit']  )->name('edit');
        Route::put('{support}',    [SupportController::class,'update'])->name('update');
        Route::delete('{support}', [SupportController::class,'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                       [ReportController::class,'index']                  )->name('index');
        Route::get('sales',                   [ReportController::class,'sales']                  )->name('sales');
        Route::get('customers',               [ReportController::class,'customers']              )->name('customers');
        Route::get('product-stock',           [ReportController::class,'productStock']           )->name('product_stock');
        Route::get('current-account-summary', [ReportController::class,'currentAccountSummary'])->name('account_summary');
        Route::get('support-request',         [ReportController::class,'supportRequest']        )->name('support');
    });

    // Actions by customer
    Route::get('actions/by-customer',[ActionController::class,'byCustomer'])
         ->name('actions.by-customer');
});

/*
|--------------------------------------------------------------------------
| Admin Panel – Tek bir ENV tabanlı admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function(){
    // Misafir admin
    Route::middleware('admin.guest')->group(function(){
        Route::get('login',  [AdminAuthController::class,'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class,'login']);
    });

    // Giriş yapmış admin
    Route::middleware('isAdmin')->group(function(){
        Route::post('logout',[AdminAuthController::class,'logout'])->name('logout');
        Route::get('/',      [AdminController::class,'index'])->name('dashboard');

        Route::resource('users', UserController::class)
     ->only(['index','create','store','show','edit','update','destroy']);
        Route::resource('customers', CustomerController::class);
    });
});