<?php

namespace App\Jobs;

use App\Events\NotificarClientePorWhatsappEvent;
use App\Listeners\EnviarWhatsappAlCliente;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Auth; // Uncomment if you need to use Auth facade

// agregar model clientes
use App\Models\Cliente; // Uncomment if you need to use the Cliente model
// agregar modelo cuentas corrientes
use App\Models\CuentaCorriente; // Uncomment if you need to use the CuentaCorr
//agregar modelo empresas 
use App\Models\Empresa; // Uncomment if you need to use the Empresa model


// use Illuminate\Support\Facades\Log; // Uncomment if you want to log information


class EnviarWhatsappAlClienteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $clientes; // Declare the $clientes property to hold the collection of clients
    public $empresa;

    /**
     * Create a new job instance.
     */
    public function __construct($empresa = null)
    {
        // If an Empresa instance is passed, use it; otherwise, use the authenticated user's empresa_id
        if ($empresa) {

            $this->empresa = Empresa::find($empresa);

            
            // Fetch all clients for the authenticated user's empresa_id
            $this->clientes = Cliente::where('empresa_id', Auth()->user()->empresa_id)
                                ->get();

            // // logger de empresa y clientes 
            // logger()->info('Empresa ID: ' . $this->empresa->id);
            // logger()->info('Empresa Nombre: ' . $this->empresa->razonSocial);
            // logger()->info('Clientes: ' . $this->clientes->count());

        } else {

            $this->empresa = NULL;


            // Fetch all clients for the authenticated user's empresa_id
            $this->clientes = Cliente::all();

            // logger()->info('no se paso una empresa traemos todos los clientes ' );

            // logger()->info('Clientes: ' . $this->clientes->count());
            
        }


    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {


        foreach ($this->clientes as $cliente) {
            // Puedes hacer algo con cada cliente aquí si es necesario
            $saldos = CuentaCorriente::where('cliente_id', $cliente->id)
                                ->orderByDesc('id')
                                ->first();
                                
            //verificcar si saldos tiene datos
            if ($saldos) {


                //controlar que el saldo sea negativo 

                if ($saldos->saldo >= 0) {
                    // Si el saldo es positivo o cero, no hacemos nada
                    continue;
                }


                if($this->empresa == null) {
                    $empresa = Empresa::find($cliente->empresa_id);

                    $razonSocial = $empresa->razonSocial;

                    $instanciaWS = $empresa->instanciaWhatsapp ?? env('instanciaWhatsappLLFactura');
                    $apikey = $empresa->tokenWhatsapp ?? env('apikeyLLFactura');
                    
                }else{

                    $razonSocial = $this->empresa->razonSocial;

                    $instanciaWS =$this->empresa->instanciaWhatsapp ?? env('instanciaWhatsappLLFactura');
                    $apikey = $this->empresa->tokenWhatsapp ?? env('apikeyLLFactura');
                    

                }

                if(env('APP_ENV') == 'local') {
                // debuguear los datos de clientes 
                    logger()->info('Cliente ID: ' . $cliente->id);
                    logger()->info('Cliente Nombre: ' . $cliente->razonSocial);
                    logger()->info('Cliente Telefono: ' . $cliente->telefono);


                    logger()->info('Cliente Saldo: ' . $saldos->saldo = null ? '' : $saldos->saldo ); // Asumiendo que 'saldo' es el campo que quieres sumar

                }


        
                // Log::info('Archivo base 64', [
                //     'base64' => $pdfBase64
                // ]);

                // format number php 
                // $saldos->saldo = number_format($saldos->saldo, 2, ',', '.'); // Formatear el saldo a dos decimales con coma como separador decimal y punto como separador de miles

                $mensaje = 'Estimado/a ' . $cliente->razonSocial .', le informamos que su saldo actual es $'. number_format($saldos->saldo, 2, ',', '.') . '. 
                    Gracias por confiar en '.$razonSocial . '.
                    
                    Conozca más sobre nuestras soluciones en https://llfactura.com';

                if(env('APP_ENV') == 'local') {
                    // debuguear el mensaje
                    logger()->info('Mensaje: ' . $mensaje);
                }

                //comprobar si el telefono del cliente existe 
                if (!empty($cliente->telefono)) {

                    if(env('APP_ENV') == 'local') {
                        // debuguear el mensaje
                        logger()->info('Exite telefono enviamos ws');
                    }

                    // Si el teléfono está vacío, no hacemos nada
                    NotificarClientePorWhatsappEvent::dispatch([
                        'clienteNombre' => $cliente->razonSocial,
                        'clienteTelefono' => $cliente->telefono,
                        'mensaje' => $mensaje,
                        'instanciaWS' => $instanciaWS,
                        'apikey' => $apikey,
                        'Base64' => null,
                    ]);
                }
        
                //comprobar si el correo del cliente existe
                if (!empty($cliente->correo)) {

                    if(env('APP_ENV') == 'local') {
                        // debuguear el mensaje
                        logger()->info('Exite correo enviamos correo');
                    }

                    // Si el correo está vacío, no hacemos nada

                    event(new \App\Events\NotificarClientePorCorreoEvent($cliente,$razonSocial .' Saldo de Cuenta', $mensaje));

                }



            }
        }





    }
}
