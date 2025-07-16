<?php
// app/Http/Controllers/AdminAuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $req)
    {
        $data = $req->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (
            $data['username'] === env('ADMIN_USERNAME')
            && $data['password'] === env('ADMIN_PASSWORD')
        ) {
            $req->session()->put('is_admin', true);
            return redirect()->route('admin.dashboard');
        }

        return back()
               ->withErrors(['username'=>'Geçersiz kimlik bilgileri'])
               ->withInput();
    }

    public function logout(Request $req)
    {
        $req->session()->forget('is_admin');
        return redirect()->route('admin.login');
    }
}
