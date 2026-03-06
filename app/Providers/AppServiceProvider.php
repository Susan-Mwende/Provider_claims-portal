<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Removed forced HTTPS to use HTTP for local development
        // URL::forceScheme('https');
        // URL::forceRootUrl(config('app.url'));
        
        try {
            DB::connection()->getPdo();
            if (Schema::hasTable('migrations')) {
                Schema::defaultStringLength(191);
            }
        } catch (\Exception $e) {
            Log::error('Database connection error: ' . $e->getMessage());
        }
    }
}