<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Novedad;

class ConsultarNovedadesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
                // Compartir la variable $novedades con todas las vistas
                View::composer('*', function ($view) {
                    $empresaId = Auth::check() ? Auth::user()->empresa_id : null;
                    $novedades = $empresaId ? Novedad::where('empresa_id', $empresaId)->count() : 0;
                    $view->with('CantNovedades', $novedades);
                });
    }
}
