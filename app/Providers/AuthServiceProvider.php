<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // \App\Models\Customer::class => \App\Policies\CustomerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Örnek gate'ler — ihtiyacınıza göre
        Gate::define('admin-only', fn (User $user) => $user->role === 'admin');

        // Global aktif kontrolü
        Gate::before(fn(User $user) => $user->active ? null : false);
    }
}
