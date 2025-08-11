<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Customer;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('isAdmin');
    }

    public function index(Request $request)
    {
        // Cache key'i versiyonlayalım ki değişiklik sonrası eski değer dönmesin
        $kpis = Cache::remember('admin:kpis:v3', 30, function () {

            // ---- Toplam Kullanıcı
            $totalUsers = (int) User::count();

            // ---- Aktif Kullanıcı (sırasıyla: active / is_active / status / last_login)
            $activeUsers = 0;
            $userTable = (new User)->getTable();

            if (Schema::hasColumn($userTable, 'active')) {
                // senin şeman: tinyint(1) active
                $activeUsers = (int) User::where('active', 1)->count();
            } elseif (Schema::hasColumn($userTable, 'is_active')) {
                $activeUsers = (int) User::where('is_active', true)->count();
            } elseif (Schema::hasColumn($userTable, 'status')) {
                $activeUsers = (int) User::where('status', 'active')->count();
            } elseif (Schema::hasColumn($userTable, 'last_login')) {
                $since = Carbon::now()->subDays(30);
                $activeUsers = (int) User::where('last_login', '>=', $since)->count();
            }

            // ---- Toplam Müşteri
            $totalCustomers = (int) Customer::count();

            // ---- Aktif Müşteri (status / is_active / updated_at son 90 gün)
            $customerTable = (new Customer)->getTable();
            $activeCustomers = 0;
            if (Schema::hasColumn($customerTable, 'status')) {
                $activeCustomers = (int) Customer::where('status', 'active')->count();
            } elseif (Schema::hasColumn($customerTable, 'is_active')) {
                $activeCustomers = (int) Customer::where('is_active', true)->count();
            } elseif (Schema::hasColumn($customerTable, 'updated_at')) {
                $activeCustomers = (int) Customer::where('updated_at', '>=', Carbon::now()->subDays(90))->count();
            }

            return [
                'users'            => $totalUsers,
                'active_users'     => $activeUsers,
                'customers'        => $totalCustomers,
                'active_customers' => $activeCustomers,
            ];
        });

        return view('admin.dashboard', compact('kpis'));
    }
}
