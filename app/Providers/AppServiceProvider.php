<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        Blade::directive('currency', function (string $expression) {
            return "<?php echo \\Illuminate\\Support\\Number::currency($expression, 'IDR', 'id'); ?>";
        });

        Blade::directive('compactCurrency', function (string $expression) {
            return "<?php echo \\Illuminate\\Support\\Number::abbreviate($expression, maxPrecision: 1, locale: 'id'); ?>";
        });
    }
}
