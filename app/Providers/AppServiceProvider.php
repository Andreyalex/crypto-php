<?php

namespace App\Providers;

use App\Robot\Binance;
use App\Services\KyivstarClientService;
use Illuminate\Support\ServiceProvider;
use function config;
use function env;

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
        //
        $this->app->singleton('binance', function () {
            return new Binance(
                env('BINANCE_API_KEY'),
                env('BINANCE_API_SECRET'),
                env('BINANCE_TEST_NET'),
            );
        });
    }
}
