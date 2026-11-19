<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pemakaian di view: @waktu($laporan->dikirim_at)
        Blade::directive('waktu', function ($expression) {
            return "<?php echo e(\\App\\Support\\Waktu::tampil($expression)); ?>";
        });
    }
}