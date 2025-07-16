<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next)
{
    // eğer session’da is_admin flag yoksa reddet
    if (! $req->session()->get('is_admin', false)) {
        abort(403, 'Yetkiniz yok.');
    }
    return $next($req);
}

}
