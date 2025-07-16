<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Middleware;

class AuthController extends Controller
{
    /* ------------- Login mevcut ------------- */
    public function showLogin()  { return view('auth.login'); }

    public function login(Request $r)
{
    $r->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // ➊ ENV admin kontrolü
    if (
        $r->username === env('ADMIN_USERNAME')
        && $r->password === env('ADMIN_PASSWORD')
    ) {
        // admin olarak işaretle
        $r->session()->put('is_admin', true);
        return redirect()->route('admin.dashboard');
    }

    // ➋ normal kullanıcı girişi
    $creds = $r->only('username','password') + ['active' => 1];
    if (Auth::attempt($creds, $r->boolean('remember'))) {
        $r->session()->regenerate();
        return redirect()->intended(route('dashboard.index'));
    }

    return back()->withErrors(['username'=>'Geçersiz kimlik bilgileri'])->withInput();
}

public function logout(Request $r)
{
    // oturumdan admin flag’i sil
    $r->session()->forget('is_admin');
    Auth::logout();
    $r->session()->invalidate();
    $r->session()->regenerateToken();
    return redirect()->route('login');
}


   
}
