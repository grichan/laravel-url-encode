<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services here (optional)
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Manually map the API routes
        $this->mapApiRoutes();
    }

    /**
     * Define the api route
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\\Http\\Controllers\\Api')
            ->group(base_path('routes/api.php'));
    }
}
