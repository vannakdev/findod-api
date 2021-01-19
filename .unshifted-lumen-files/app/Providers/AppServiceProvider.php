<?php

namespace App\Providers;

use App\Observers\PropertiesObserver;
use App\Observers\RatingsObserver;
use App\Observers\RequestViewingObserver;
use App\Observers\UsersObserver;
use App\Properties;
use App\Ratings;
use App\RequestViewing;
use App\Users;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Ratings::observe(RatingsObserver::class);
        RequestViewing::observe(RequestViewingObserver::class);
        Users::observe(UsersObserver::class);
        Properties::observe(PropertiesObserver::class);

        //Uncomment this if you want to log raw sql query
        \DB::listen(function ($query) {
            // $query->sql
            // $query->bindings
            // $query->time
            \Log::info($query->sql);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });
    }
}
