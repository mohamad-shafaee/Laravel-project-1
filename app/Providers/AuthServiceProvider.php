<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        Gate::define('update-post', function(User $user, Post $post){
            return $user->id === $post->user_id;
            // It is better to check the phone_verified_at and email_verified_at in other gates.

        });

        Gate::define('update-user-profile', function(User $user, $user_id){
            //check if the selected user is the authenticated user
            // here === dont work since type of values may not be same.
            return $user->id == $user_id;

        });

        //
    }
}
