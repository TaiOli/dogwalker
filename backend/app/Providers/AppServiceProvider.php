<?php

namespace App\Providers;

use App\Models\Tour;
use App\Observers\TourObserver;
use App\Repositories\Services\Contracts\UserServiceInterface;
use App\Repositories\Services\Contracts\DogServiceInterface;
use App\Repositories\Services\Contracts\TourServiceInterface;
use App\Repositories\Services\Contracts\EvaluationServiceInterface;
use App\Services\UserService;
use App\Services\DogService;
use App\Services\TourService;
use App\Services\EvaluationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return config('app.frontend_url') . '/resetar-senha?token=' . $token . '&email=' . urlencode($user->email);
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip());
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)->by($request->input('email') . '|' . $request->ip());
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Tour::observe(TourObserver::class);
    }
}