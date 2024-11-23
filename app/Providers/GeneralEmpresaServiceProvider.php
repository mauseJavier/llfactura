<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Empresa;



class GeneralEmpresaServiceProvider extends ServiceProvider
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
                // Registrar un View Composer para todas las vistas
                View::composer('*', function ($view) {
                    // Obtener los datos del modelo que quieres compartir
                    // $resultados = Empresa::find(Auth()->user()->empresa_id);
                    $resultados = Empresa::all();


                    // Compartir con todas las vistas
                    // View::share('generalEmpresa', $resultados);
        
                    // Compartir los datos con todas las vistas
                    $view->with('generalEmpresa', $resultados);
                });
    }
}
