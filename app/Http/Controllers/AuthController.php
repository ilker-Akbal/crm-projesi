<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /* ------------- Login mevcut ------------- */
    public function showLogin()  { return view('auth.login'); }

    public function login(Request $r)
    {
        $creds = $r->validate(['username'=>'required','password'=>'required']);
        $creds['active'] = 1;                                   // aktif kullanıcı

        if (Auth::attempt($creds, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended(route('dashboard.index'));
        }
        return back()->withErrors(['username'=>'Invalid credentials'])->withInput();
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login');
    }

    /* ------------- Register ekle ------------- */
   
}
