<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

use App\Jobs\EnviarWhatsappAlClienteJob;
 

        // Artisan::command('inspire', function () {
        //     $this->comment(Inspiring::quote());
        // })->purpose('Display an inspiring quote')->everyFiveSeconds();
        
        
        Schedule::command('repetirGastoMinuto')->everyMinute();
        Schedule::command('repetirGastoMes')->monthly();


        // Aquí puedes añadir comandos programados
        // Ejecutar el job EnviarWhatsappAlClienteJob el 1 y 4 de cada mes a las 10:00 AM
        // $schedule->job(new \App\Jobs\EnviarWhatsappAlClienteJob())
        //     ->monthlyOn(1, '10:00');
        // $schedule->job(new \App\Jobs\EnviarWhatsappAlClienteJob())
        //     ->monthlyOn(4, '10:00');

            // Dispatch the job to the "heartbeats" queue on the "sqs" connection...

        if(env('APP_NAME') === 'LLFactura') {
            
            // En producción, puedes ajustar la frecuencia según sea necesario
            Schedule::job(new EnviarWhatsappAlClienteJob)->monthlyOn(3, '10:00');
            Schedule::job(new EnviarWhatsappAlClienteJob)->monthlyOn(10, '10:00');
        } else {
            
            // Solo para el entorno local, ejecuta el job cada minuto APP_NAME=LLFactura
            // Schedule::job(new EnviarWhatsappAlClienteJob)->everyMinute();
        }




