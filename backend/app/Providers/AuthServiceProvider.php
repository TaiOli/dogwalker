<?php

namespace App\Providers;

use App\Models\Dog;
use App\Models\Tour;
use App\Models\User;
use App\Policies\DogPolicy;
use App\Policies\TourPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Tour::class => TourPolicy::class,
        User::class => UserPolicy::class,
        Dog::class => DogPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}