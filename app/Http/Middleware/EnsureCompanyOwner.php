<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCompanyOwner
{
    /**
     * Şirket kaydının customer_id alanı ile
     * giriş yapan kullanıcının customer_id'si eşleşmeli.
     */
    public function handle(Request $request, Closure $next)
    {
        $company = $request->route('company');   // route-model binding sayesinde

        if (! $company || $company->customer_id !== auth()->user()->customer_id) {
            abort(403, 'Bu kayda erişim yetkiniz yok.');
        }

        return $next($request);
    }
}
