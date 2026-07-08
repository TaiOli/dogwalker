<?php

namespace App\Providers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\TourRepositoryInterface;
use App\Repositories\Interfaces\DogRepositoryInterface;
use App\Repositories\Interfaces\EvaluationRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\TourRepository;
use App\Repositories\DogRepository;
use App\Repositories\EvaluationRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TourRepositoryInterface::class, TourRepository::class);
        $this->app->bind(DogRepositoryInterface::class, DogRepository:: class);
        $this->app->bind(EvaluationRepositoryInterface::class, EvaluationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
