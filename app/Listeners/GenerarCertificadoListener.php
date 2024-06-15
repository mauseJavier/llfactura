<?php

namespace App\Listeners;


use Afip;


use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Carbon;


use App\Events\GenerarCertificadoEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerarCertificadoListener
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
    public function handle(GenerarCertificadoEvent $event)
    {

        // dump($event->cuit);
        // dump($event->clave);
        // dump($event->cuit);
        // dd($event);
        
                // CUIT al cual le queremos generar el certificado  27291079771 VASQUEZ FABIOLA ALTRICIA [27-29107977-1]
                $tax_id = $event->cuit; 

                // Usuario para ingresar a AFIP.
                // Para la mayoria es el mismo CUIT, pero al administrar
                // una sociedad el CUIT con el que se ingresa es el del administrador
                // de la sociedad.
                $username = strval($event->cuit); //'27291079771'''''; 

                // Contraseña para ingresar a AFIP. Juliana2016
                $password = $event->clave; //'Juliana2016''''';

                // Alias para el certificado (Nombre para reconocerlo en AFIP)
                // un alias puede tener muchos certificados, si estas renovando
                // un certificado pordes utilizar le mismo alias
                $alias = Carbon::now()->format('Ymd').'llfactura';

                // Creamos una instancia de la libreria
                $afip = new Afip(array(
                    'CUIT' => $tax_id,
                    'access_token' => env('tokenAFIPsdk'),
                    'production' => TRUE
                ));

                // Creamos el certificado (¡Paciencia! Esto toma unos cuantos segundos)
                $res = $afip->CreateCert($username, $password, $alias);

                // Mostramos el certificado por pantalla
                // dump($res->cert);

                // Mostramos la key por pantalla
                // dump($res->key);

                // ATENCION! Recorda guardar el cert y key ya que 
                // la libreria por seguridad no los guarda, esto depende de vos.
                // Si no lo guardas vas tener que generar uno nuevo con este metodo

                if (! Storage::put('public/'.$event->cuit.'/cert.crt', $res->cert)) {
                    // The file could not be written to disk...
                    dump('ERROR AL GUARDAR CERT');
                }else{
                    // dump('guardado ok cert');
                }
                if (! Storage::put('public/'.$event->cuit.'/key.key', $res->key)) {
                    // The file could not be written to disk...
                    dump('ERROR AL GUARDAR KEY');
                }else{
                    // dump('guardado ok key');
                }

                ////////////////////////////////////////7
                //////////////////////////////////////7
                //////////7/////AUTORIZAMOS EL WEB SERVISE 
                // Alias del certificado a autorizar (previamente creado)
                    // $alias = 'afipsdk';

                    // Id del web service a autorizar
                    $wsid = 'wsfe';

                    // Creamos una instancia de la libreria
                    // $afip = new Afip(array('CUIT' => $tax_id ));

                    // Creamos la autorizacion (¡Paciencia! Esto toma unos cuantos segundos)
                    $res = $afip->CreateWSAuth($username, $password, $alias, $wsid);

                    // Mostramos el resultado por pantalla
                    // dd($res);
                    if (! Storage::put('public/'.$event->cuit.'/estadoAutorizacionWS', $res->status)) {
                        // The file could not be written to disk...
                        dump('ERROR AL GUARDAR KEY');
                    }else{
                        // dump('guardado ok key');
                    }
    }
}
