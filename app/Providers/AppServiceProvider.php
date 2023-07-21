<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\TimeKeeper;
use App\Models\Upcomingevent;
use App\Observers\EmployeeObserver;
use App\Observers\TimeKeeperObserver;
use App\Observers\UpcomingEventObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        TimeKeeper::observe(TimeKeeperObserver::class);
        Upcomingevent::observe(UpcomingEventObserver::class);
        Schema::defaultStringLength(191); 
    }
}
