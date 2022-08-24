<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Admin\Models\Driver;
use Modules\Admin\Models\User;

class ModelRelationMorphProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::MorphMap([
            config('constant.feedback.driver') => User::class,
            config('constant.feedback.user') => Driver::class,
        ]);
    }
}
