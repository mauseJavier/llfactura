<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos de la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define las tareas programadas.
     */
    protected function schedule(Schedule $schedule)
    {
        // Aquí puedes añadir comandos programados
        // Ejecutar el job EnviarWhatsappAlClienteJob el 1 y 4 de cada mes a las 10:00 AM
        $schedule->job(new \App\Jobs\EnviarWhatsappAlClienteJob())
            ->monthlyOn(1, '10:00');
        $schedule->job(new \App\Jobs\EnviarWhatsappAlClienteJob())
            ->monthlyOn(4, '10:00');
    }
}
