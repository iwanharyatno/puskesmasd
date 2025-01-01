<?php

namespace App\Providers;

use App\Models\Queue;
use App\Models\Service;
use App\Models\User;
use App\Policies\PatientPolicy;
use App\Policies\QueuePolicy;
use App\Policies\ServicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(User::class, PatientPolicy::class);
        Gate::policy(Queue::class, QueuePolicy::class);

        Gate::define('viewApiDocs', function (User $user) {
            return true;
        });
    }
}
