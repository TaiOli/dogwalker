<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Services\Contracts\UserServiceInterface;
use App\Repositories\Services\Contracts\DogServiceInterface;
use App\Repositories\Services\Contracts\TourServiceInterface;
use App\Repositories\Services\Contracts\EvaluationServiceInterface;
use App\Services\UserService;
use App\Services\DogService;
use App\Services\TourService;
use App\Services\EvaluationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(DogServiceInterface::class, DogService::class);
        $this->app->bind(TourServiceInterface::class, TourService::class);
        $this->app->bind(EvaluationServiceInterface::class, EvaluationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}