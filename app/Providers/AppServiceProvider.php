<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\View;
use App\Models\Empresa;

use Illuminate\Support\Facades\Auth;


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
        //
                // Obtener los datos del modelo que quieres compartir
                // $resultados = Empresa::find(Auth()->user()->empresa_id);
                $resultados = Empresa::all();


                // Compartir con todas las vistas
                View::share('generalEmpresa', $resultados);
    }
    // ...

    protected $listen = [
        // ...
        Login::class => [
            'App\Listeners\LogLastLoginTime',
        ],
    ];
}


