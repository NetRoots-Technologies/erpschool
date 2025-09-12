<?php

namespace App\Providers;


use App\Models\Admin\Vendor;
use App\Observers\VendorObserver;
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
        Schema::defaultStringLength(191);
        ini_set('memory_limit', '1024M');
        ini_set('max_input_vars', '5000');
        Vendor::observe(VendorObserver::class);

    }
}
