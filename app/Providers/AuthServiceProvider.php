<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Product::class => \App\Policies\ShopPolicy::class,
        \App\Models\Brand::class => \App\Policies\ShopPolicy::class,
        \App\Models\Category::class => \App\Policies\ShopPolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Ensure admins bypass policy checks
        Gate::before(function (?User $user, $ability) {
            if ($user && in_array($user->role, ['admin', 'super_admin'], true)) {
                return true;
            }
        });

        // Single gate to protect shop management (products, brands, categories)
        Gate::define('manage-shop', fn (User $user) => in_array($user->role, ['admin', 'super_admin'], true));
    }
}
