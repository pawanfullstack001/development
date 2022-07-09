<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App\ViewComposers\DriverComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        View::composer(
             '*', DriverComposer::class
            ); 
            if(config('app.env') === 'production') {
                \URL::forceScheme('https');
            }

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
