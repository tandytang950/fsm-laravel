<?php

namespace App\Providers;

use App\Contracts\FiniteStateMachineBuilderServiceInterface;
use App\Contracts\FiniteStateMachineProcessorServiceInterface;
use App\Contracts\FiniteStateMachineTransitionServiceInterface;
use App\Services\FiniteStateMachineBuilderService;
use App\Services\FiniteStateMachineProcessorService;
use App\Services\FiniteStateMachineTransitionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FiniteStateMachineBuilderServiceInterface::class,
            FiniteStateMachineBuilderService::class);

        $this->app->bind(FiniteStateMachineProcessorServiceInterface::class,
            FiniteStateMachineProcessorService::class);

        $this->app->bind(FiniteStateMachineTransitionServiceInterface::class,
            FiniteStateMachineTransitionService::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
