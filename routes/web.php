<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
// Homepage (dashboard)
Route::view('/', 'dashboard')->name('dashboard');

// Subpages
Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
Route::view('/orders', 'orders.index')->name('orders.index');
Route::view('/products', 'products.index')->name('products.index');
Route::view('/accounts', 'accounts.index')->name('accounts.index');
Route::view('/reports', 'reports.index')->name('reports.index');
Route::view('/support', 'support.index')->name('support.index');
Route::view('/users', 'users.index')->name('users.index');
Route::view('/reminders', 'reminders.index')->name('reminders.index');
