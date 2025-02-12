<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //Passport::routes();
        //
        Passport::tokensCan([
            'admin'=>'Admin access',
            'influencer' => 'Influencer access'
        ]);

        Gate::define('view', function(User $user , $model){
            return $user->hasAccess("view_{$model}") || $user->hasAccess("edit_{$model}");
        });
        Gate::define('edit', function(User $user , $model){
            return  $user->hasAccess("edit_{$model}");
        });
    }
}
