<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Buraya şimdilik ihtiyaç yok
    }

    public function boot(): void
    {
        /**
         * Username + password + active kontrolü ile kimlik doğrulama
         */
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first();

            return $user
                && Hash::check($request->password, $user->password)
                && $user->active
                ? $user
                : null;
        });
    }
}
