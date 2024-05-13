<?php

namespace Gohrco\LaravelForm;

use Gohrco\LaravelForm\View\Components\Field;
use Gohrco\LaravelForm\View\Components\Formclose;
use Gohrco\LaravelForm\View\Components\Formopen;
use Gohrco\LaravelForm\View\Components\Javascript;
use Illuminate\Support\ServiceProvider;

class LaravelFormServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravelform');
        $this->loadViewComponentsAs('laravelform', [
            Field::class,
            Formclose::class,
            Formopen::class,
            Javascript::class,
        ]);

        $this->publishes([
            __DIR__ . '/resources/views' => base_path('/resources/views/vendor/laravelform'),
        ], 'laravelform-views');

        $this->publishes([
            __DIR__ . '/resources/forms' => base_path('/resources/views/vendor/laravelform/forms'),
        ], 'laravelform-forms');

        $this->mergeConfigFrom(__DIR__ . '/config.php', 'laravelform');
        $this->publishes([
            __DIR__ . '/config.php' => config_path('laravelform.php'),
        ]);
    }
}
