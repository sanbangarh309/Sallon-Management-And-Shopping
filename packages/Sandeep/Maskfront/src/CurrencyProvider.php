<?php

namespace Sandeep\Maskfront;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class CurrencyProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Money::setLocale($this->app->make('translator')->getLocale());
        Currency::setCurrencies(config('money'));

        // Register blade directives
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('money', function ($expression) {
                return "<?php echo money($expression); ?>";
            });
        });

        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $bladeCompiler->directive('currency', function ($expression) {
                return "<?php echo currency($expression); ?>";
            });
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
