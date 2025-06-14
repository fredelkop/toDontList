<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Models\Excuse;
use App\Policies\TaskPolicy;
use App\Policies\ExcusePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected $policies = [
        Task::class => TaskPolicy::class,
        Excuse::class => ExcusePolicy::class,
    ];
}
