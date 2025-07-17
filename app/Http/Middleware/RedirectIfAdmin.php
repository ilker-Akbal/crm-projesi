<?php
// app/Http/Middleware/RedirectIfAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->get('is_admin', false)) {
            // Zaten admin oturumu varsa doğrudan dashboard’a
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
