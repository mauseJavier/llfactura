<?php

namespace App\Listeners;

use App\Events\NotificarClientePorWhatsappEvent;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Http;


class EnviarWhatsappAlCliente 
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificarClientePorWhatsappEvent $event): void
    {


        // dd($event);
    
        $clienteNombre = $event->clienteNombre;
        $clienteTelefono = $event->clienteTelefono;
        $mensaje = $event->mensaje;
        $instanciaWS = $event->instanciaWS;
        $apikey = $event->apikey;
        $Base64 = $event->Base64;
        // $tokenTelegram = $event->tokenTelegram;

        // dd($clienteNombre, $clienteTelefono, $mensaje, $instanciaWS, $apikey, $tokenTelegram);
        
        // Puedes usar esto para depurar temporalmente:
        // logger()->info("cliente nombre: $clienteNombre");
        // logger()->info("cliente telefono: $clienteTelefono");
        // logger()->info("mensaje: $mensaje");
        // logger()->info("instanciaWS: $instanciaWS");
        // logger()->info("apikey: $apikey");
        // logger()->info("tokenTelegram: $tokenTelegram");

                $response = Http::withHeaders([
                    'apikey' => $apikey,
                ])->post('https://evo.llservicios.ar/message/sendText/'.$instanciaWS, [
                    'number' => '549'.$clienteTelefono,
                    'text' => $mensaje ,

                ]);

        // logger()->info($response);

        if($Base64){

            $response = Http::withHeaders([
                    'apikey' => $apikey,
                    'Content-Type'=> 'application/json',
        
                ])->post('https://evo.llservicios.ar/message/sendMedia/'.$instanciaWS, [ //https://localhost:8080/message/sendMedia/dfgdfg '{"media":"dsgergdfgdfg","mediatype":"image","number":"33333"}'
                    'number' => '549'.$clienteTelefono,
                    'media'=>$Base64,
                    'mediatype'=>'document',//image
                    'fileName'=>'Pago Cliente:'. $clienteNombre .'.pdf',//
        
        
                ]);


                // logger()->info($response);


        }



    }
}
