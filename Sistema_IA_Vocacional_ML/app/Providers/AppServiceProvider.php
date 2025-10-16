<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\UserRepository::class, function ($app) {
            return new \App\Repositories\UserRepository();
        });

        $this->app->bind(\App\Repositories\TestRepository::class, function ($app) {
            return new \App\Repositories\TestRepository();
        });

        $this->app->bind(\App\Repositories\CareerRepository::class, function ($app) {
            return new \App\Repositories\CareerRepository();
        });

        $this->app->bind(\App\Repositories\GradeRepository::class, function ($app) {
            return new \App\Repositories\GradeRepository();
        });

        $this->app->bind(\App\Repositories\ClusteringRepository::class, function ($app) {
            return new \App\Repositories\ClusteringRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
