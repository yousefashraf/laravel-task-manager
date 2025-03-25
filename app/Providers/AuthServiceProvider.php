<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::policy(Task::class, TaskPolicy::class);
        Passport::tokensCan([
            'manager' => 'Manage all tasks and assignments',
            'user' => 'View and update assigned tasks',
        ]);
        Passport::ignoreCsrfToken();
        Passport::enablePasswordGrant();
    }
}
