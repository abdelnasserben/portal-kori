<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
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
        Blade::directive('dateIso', function (string $expression): string {
            return "<?php echo e((function (\$value, \$fallback = '') {
                if (blank(\$value)) {
                    return \$fallback;
                }

                try {
                    return \Carbon\Carbon::parse(\$value)->utc()->format('d/m/Y H:i');
                } catch (\\Throwable) {
                    return \$fallback;
                }
            })(...[{$expression}])); ?>";
        });
        
        Route::middleware('web')->group(base_path('routes/auth.php'));
        Route::middleware('web')->group(base_path('routes/backoffice.php'));
    }
}
