<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setLocale(LC_ALL,"id_ID.utf8");

        \Carbon\Carbon::setLocale("id_ID.utf8");

        date_default_timezone_set('Asia/Jakarta');
    }
}
