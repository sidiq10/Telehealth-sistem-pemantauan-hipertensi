<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\HealthRecord;
use App\Observers\HealthRecordObserver;

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
        HealthRecord::observe(HealthRecordObserver::class);
    }
}
