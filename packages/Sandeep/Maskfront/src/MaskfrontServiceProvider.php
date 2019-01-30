<?php

namespace Sandeep\Maskfront;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class MaskfrontServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'maskFront');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/san_routes.php');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'maskLang');
        // require(__DIR__.'/functions.php');
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/maskfront.php', 'maskfront');
        $this->mergeConfigFrom(__DIR__.'/../config/money.php', 'money');
        $this->app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Illuminate\Session\Middleware\StartSession');
        $loader = AliasLoader::getInstance();
        // Collective HTML & Form Helper
        // $loader->alias('San_Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);
        $loader->alias('mask_middle', \Sandeep\Maskfront\Middleware\MaskMiddleware::class);
        // For Front Helper
        $loader->alias('San_Help', \Sandeep\Maskfront\San_Help::class);
        $loader->alias('San_Payfort', \Sandeep\Maskfront\Library\Payfort\PayfortIntegration::class);
        // Register the service the package provides.
        $this->app->singleton('maskfront', function ($app) {
            return new Maskfront;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['maskfront'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/maskfront.php' => config_path('maskfront.php'),
        ], 'maskfront.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/sandeep'),
        ], 'maskfront.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/sandeep'),
        ], 'maskfront.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/sandeep'),
        ], 'maskfront.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
