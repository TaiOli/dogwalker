<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\TourRepositoryInterface;
use App\Repositories\Contracts\DogRepositoryInterface;
use App\Repositories\Contracts\EvaluationRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\TourRepository;
use App\Repositories\Eloquent\DogRepository;
use App\Repositories\Eloquent\EvaluationRepository;

class AppServiceProvider extends ServiceProvider
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
