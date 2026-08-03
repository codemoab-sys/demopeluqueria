<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Empresa;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrapFive();

        // Compartir la empresa configurada con todas las vistas
        View::composer('*', function ($view) {
            try {
                $empresa = auth()->check()
                    ? Empresa::where('id', auth()->user()->empresa_id)->first()
                    : Empresa::first();
                if ($empresa && $empresa->simbolo_moneda !== 'S/.') {
                    $empresa->simbolo_moneda = 'S/.';
                }
                if ($empresa && $empresa->codigo_moneda !== 'PEN') {
                    $empresa->codigo_moneda = 'PEN';
                }
                $view->with('empresaConfig', $empresa);
            } catch (\Throwable $e) {
                $view->with('empresaConfig', null);
            }
        });
    }
}
