<?php

namespace App\Providers;

use App\Console\Commands\UpdateBatchNumber;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $commands = [
        UpdateBatchNumber::class
    ];
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
        if($this->app->runningInConsole()) {
            $this->commands([
                UpdateBatchNumber::class
            ]);
        }
    }
}
