<?php

namespace A2design\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFormBuilder();
        $this->mergeConfigFrom(
            __DIR__.'/config/form.php', 'form'
        );
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'form');
        $this->publishes([
            __DIR__.'/config/form.php' => config_path('form.php'),
        ]);
    }

    /**
     * Register the form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton(FormBuilder::class, function ($app) {

            return new FormBuilder($app['view'], $app['session.store'], $app['request']);
        });
    }
}
