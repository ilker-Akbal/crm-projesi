<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    AdminAuthController,
    DashboardController,
    AdminController,
    CompanyController,
    ContactController,
    OrderController,
    OrderPdfController,
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
    ProductSerialController,
    OrderSerialController
};

/*
|--------------------------------------------------------------------------
| 1) Misafir (Guest) Rotaları
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| 2) Auth’lu Kullanıcı Rotaları
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* ---------- Oturum ---------- */
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    /* ---------- Dashboard ---------- */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    /* ---------- PDF: Şirketler ---------- */
    Route::get('companies/pdf',        [CompanyController::class, 'exportPdf'])->name('companies.pdf');
    Route::get('companies/pdf/filter', [CompanyController::class, 'exportPdfWithFilter'])->name('companies.pdf.filter');

    /* ---------- PDF: Siparişler ---------- */
    Route::get('orders/pdf',        [OrderPdfController::class, 'exportPdf'])->name('orders.pdf');
    Route::get('orders/pdf/filter', [OrderPdfController::class, 'exportPdfWithFilter'])->name('orders.pdf.filter');

    /* ---------- PDF: Hesap Hareketleri ---------- */
    Route::get('movements/pdf',        [MovementController::class, 'exportPdf'])->name('movements.pdf');
    Route::get('movements/pdf/filter', [MovementController::class, 'exportPdfWithFilter'])->name('movements.pdf.filter');

    /* ---------- CRM Kaynakları ---------- */
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

    /* ---------- Sınırlı işlemler ---------- */
    Route::resource('orders', OrderController::class)
         ->only(['index','create','store','edit','update','destroy']);

    Route::resource('product_stocks', ProductStockController::class)
         ->only(['index','create','store']);

    /* ---------- Seri No işlemleri ---------- */
    Route::resource('product_serials', ProductSerialController::class)
         ->only(['index','create','store','destroy']);

    Route::get('products/{product}/serials_create', [ProductController::class,'createSerials'])
         ->name('products.serials.create');
    Route::post('products/{product}/serials_create', [ProductController::class,'storeSerials'])
         ->name('products.serials.store');

    Route::get('/orders/{order}/serials_create', [OrderSerialController::class, 'create'])
         ->name('orders.serials.create');
    Route::post('/orders/{order}/serials', [OrderSerialController::class, 'store'])
         ->name('orders.serials.store');

    /* ---------- Support ---------- */
    Route::resource('product_stocks', ProductStockController::class)
         ->only(['index','create','store','edit','update']);

    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/',             [SupportController::class, 'index'])->name('index');
        Route::get('create',        [SupportController::class, 'create'])->name('create');
        Route::get('pending',       [SupportController::class, 'pending'])->name('pending');
        Route::get('resolved',      [SupportController::class, 'resolved'])->name('resolved');
        Route::get('{support}',     [SupportController::class, 'show'])->name('show');
        Route::post('/',            [SupportController::class, 'store'])->name('store');
        Route::get('{support}/edit',[SupportController::class, 'edit'])->name('edit');
        Route::put('{support}',     [SupportController::class, 'update'])->name('update');
        Route::delete('{support}',  [SupportController::class, 'destroy'])->name('destroy');
    });

    /* ---------- Raporlar ---------- */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                       [ReportController::class, 'index'])->name('index');
        Route::get('sales',                   [ReportController::class, 'sales'])->name('sales');
        Route::get('customers',               [ReportController::class, 'customers'])->name('customers');
        Route::get('product-stock',           [ReportController::class, 'productStock'])->name('product_stock');
        Route::get('current-account-summary', [ReportController::class, 'currentAccountSummary'])->name('account_summary');
        Route::get('support-request',         [ReportController::class, 'supportRequest'])->name('support');
    });

    /* ---------- Actions by Customer ---------- */
    Route::get('actions/by-customer', [ActionController::class, 'byCustomer'])
         ->name('actions.by-customer');
});

/*
|--------------------------------------------------------------------------
| 3) Admin Panel Rotaları
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login', function () {
        Auth::logout();
        return app(AdminAuthController::class)->showLogin();
    })->name('login');

    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware(['isAdmin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/',       [AdminController::class, 'index'])->name('dashboard');
        Route::resource('users',     UserController::class);
        Route::resource('customers', CustomerController::class);
    });
});

/*
|--------------------------------------------------------------------------
| 4) Admin alanından çıkınca otomatik logout
|--------------------------------------------------------------------------
*/
Route::any('{path}', function (\Illuminate\Http\Request $request, $path) {
    if (Auth::check() && Auth::user()->is_admin) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/' . ltrim($path, '/'));
    }
    abort(404);
})->where('path', '^(?!admin).*');
