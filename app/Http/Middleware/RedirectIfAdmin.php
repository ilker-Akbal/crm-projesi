<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAdmin
{
    public function handle(Request $req, Closure $next)
    {
        if ($req->session()->get('is_admin', false)) {
            return redirect()->route('admin.dashboard');
        }
        return $next($req);
    }
}
