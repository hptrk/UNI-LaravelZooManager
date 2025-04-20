<?php

namespace App\Providers;

use App\Models\Animal;
use App\Models\Enclosure;
use App\Policies\AnimalPolicy;
use App\Policies\EnclosurePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Enclosure::class => EnclosurePolicy::class,
        Animal::class => AnimalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}