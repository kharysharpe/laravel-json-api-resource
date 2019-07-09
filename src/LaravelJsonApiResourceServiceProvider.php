<?php

namespace Kharysharpe\LaravelJsonApiResource;

use Illuminate\Support\ServiceProvider;

class LaravelJsonApiResourceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-json-api-resource');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-json-api-resource');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            // $this->publishes([
            //     __DIR__.'/../config/config.php' => config_path('laravel-json-api-resource.php'),
            // ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-json-api-resource'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-json-api-resource'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-json-api-resource'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-json-api-resource');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-json-api-resource', function () {
            return new LaravelJsonApiResource;
        });
    }
}
