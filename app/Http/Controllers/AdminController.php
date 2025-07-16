<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;
use App\Middleware;
class AdminController extends Controller
{
    public function __construct()
    {
        // Admin middleware’iniz zaten burada çalışacaksa bunu ekleyin:
        $this->middleware('isAdmin');
    }

    /**
     * GET /admin
     */
    public function index()
    {
        // resources/views/admin/dashboard.blade.php
        return view('admin.dashboard');
    }
}
