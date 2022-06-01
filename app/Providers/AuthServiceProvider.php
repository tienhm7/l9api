<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();

            // Token Lifetimes
            Passport::tokensExpireIn(now()->addDays(env('TOKENS_EXPIRE_IN', 15)));
            Passport::refreshTokensExpireIn(now()->addDays(env('REFRESH_TOKENS_EXPIRE_IN', 30)));
            Passport::personalAccessTokensExpireIn(now()->addMonths(env('PERSONAL_ACCESS_TOKENS_EXPIRE_IN', 6)));
        }

    }
}
