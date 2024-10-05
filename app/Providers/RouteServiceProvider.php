<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configurations.
     *
     * @return void
     */
    public function boot()
    {
        // Optional: Configure rate limiting or other boot-time configurations here.

        $this->configureRateLimiting();

        // Define how routes are loaded.
        $this->routes(function () {
            // API Routes
            Route::prefix('api') // Prefixes all routes in api.php with 'api'
                ->middleware('api') // Applies the 'api' middleware group
                ->group(base_path('routes/api.php')); // Loads routes from routes/api.php

            // Web Routes
            Route::middleware('web') // Applies the 'web' middleware group
                ->group(base_path('routes/web.php')); // Loads routes from routes/web.php
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * This method is optional and can be customized based on your needs.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // Example: Configure API rate limiting using Laravel's RateLimiter facade.
        // This is useful to prevent abuse of your API endpoints.
        /*
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
        */
    }
}
