<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DAO\Interfaces\UserDAOInterface;
use App\DAO\Interfaces\TestDAOInterface;
use App\DAO\Interfaces\CareerDAOInterface;
use App\DAO\Interfaces\GradeDAOInterface;
use App\DAO\Interfaces\ClusteringDAOInterface;
use App\DAO\UserDAO;
use App\DAO\TestDAO;
use App\DAO\CareerDAO;
use App\DAO\GradeDAO;
use App\DAO\ClusteringDAO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserDAOInterface::class, UserDAO::class);
        $this->app->bind(TestDAOInterface::class, TestDAO::class);
        $this->app->bind(CareerDAOInterface::class, CareerDAO::class);
        $this->app->bind(GradeDAOInterface::class, GradeDAO::class);
        $this->app->bind(ClusteringDAOInterface::class, ClusteringDAO::class);

        $this->app->bind(\App\Repositories\UserRepository::class, function ($app) {
            return new \App\Repositories\UserRepository($app->make(\App\DAO\Interfaces\UserDAOInterface::class));
        });

        $this->app->bind(\App\Repositories\TestRepository::class, function ($app) {
            return new \App\Repositories\TestRepository($app->make(\App\DAO\Interfaces\TestDAOInterface::class));
        });

        $this->app->bind(\App\Repositories\CareerRepository::class, function ($app) {
            return new \App\Repositories\CareerRepository($app->make(\App\DAO\Interfaces\CareerDAOInterface::class));
        });

        $this->app->bind(\App\Repositories\GradeRepository::class, function ($app) {
            return new \App\Repositories\GradeRepository($app->make(\App\DAO\Interfaces\GradeDAOInterface::class));
        });

        $this->app->bind(\App\Repositories\ClusteringRepository::class, function ($app) {
            return new \App\Repositories\ClusteringRepository($app->make(\App\DAO\Interfaces\ClusteringDAOInterface::class));
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
