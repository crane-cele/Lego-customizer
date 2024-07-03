<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Models\Set;
use App\Models\Part;
use App\Models\CustomSet;
use App\Policies\SetPolicy;
use App\Policies\PartPolicy;
use App\Policies\CustomSetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Set::class => SetPolicy::class,
        Part::class => PartPolicy::class,
        CustomSet::class => CustomSetPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        // Passport::routes();
    }
}
