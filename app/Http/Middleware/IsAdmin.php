<?php
// app/Http/Middleware/IsAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->get('is_admin', false)) {
            // Admin değilse login sayfasına yönlendir
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
