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
            __DIR__.'/config/form.php', FormBuilder::CONFIG_NAME
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
            __DIR__.'/config/form.php' => config_path(FormBuilder::CONFIG_NAME . '.php'),
            __DIR__.'/resources/views' => resource_path('views/vendor/form'),
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

            return new FormBuilder($app['view'], $app['session.store'], $app['routes'], $app['config'], $app['request']);
        });
    }
}
