<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Services\PdfComprobanteGenerar;
use App\Events\NotificarClientePorWhatsappEvent;



class EnviarPdfComprobanteJob implements ShouldQueue
{
    use Queueable;

    protected $tipoComprobante;
    protected $comprobante_id;
    protected $formato;
    protected $clienteNombre;
    protected $clienteTelefono;
    protected $mensaje;
    protected $usuarioId;

    public function __construct($tipoComprobante,$comprobante_id, $formato, $clienteNombre, $clienteTelefono, $mensaje, $usuarioId)
    {
        $this->tipoComprobante = $tipoComprobante;
        $this->comprobante_id = $comprobante_id;
        $this->formato = $formato;
        $this->clienteNombre = $clienteNombre;
        $this->clienteTelefono = $clienteTelefono;
        $this->mensaje = $mensaje;
        $this->usuarioId = $usuarioId;


        // Log::info('Constructor el Job EnviarPdfComprobanteJob', [
        //     'comprobante_id' => $this->comprobante_id,
        //     'formato' => $this->formato,
        //     'clienteNombre' => $this->clienteNombre,
        //     'clienteTelefono' => $this->clienteTelefono,
        //     'mensaje' => $this->mensaje,
        //     'usuarioId' => $this->usuarioId,
        // ]);

    }


    public function handle()
    {
        // Log::info('Iniciando el Job EnviarPdfComprobanteJob', [
        //     'comprobante_id' => $this->comprobante_id,
        //     'formato' => $this->formato,
        //     'clienteNombre' => $this->clienteNombre,
        //     'clienteTelefono' => $this->clienteTelefono,
        //     'mensaje' => $this->mensaje,
        //     'usuarioId' => $this->usuarioId,
        // ]);
    
        try {
            $PdfComprobanteGenerar = new PdfComprobanteGenerar();
            // Log::info('Instancia de PdfComprobanteGenerar creada.');
    
            if ($this->tipoComprobante == 'presupuesto'){

                $pdfBase64 = $PdfComprobanteGenerar->obtenerPdfBase64Presupuesto($this->comprobante_id, 'A4', $this->usuarioId);

                
            }else{

                $pdfBase64 = $PdfComprobanteGenerar->obtenerPdfBase64($this->comprobante_id, $this->formato, $this->usuarioId);
                // Log::info('PDF generado en Base64.', ['pdfBase64' => substr($pdfBase64, 0, 100) . '...']); // Muestra solo los primeros 100 caracteres
            }

    
            $instanciaWS = env('instanciaWhatsappLLFactura');
            $apikey = env('apikeyLLFactura');

            // Log::info('Credenciales de WhatsApp obtenidas.', [
            //     'instanciaWS' => $instanciaWS,
            //     'apikey' => $apikey,
            // ]);
    
            NotificarClientePorWhatsappEvent::dispatch([
                'clienteNombre' => $this->clienteNombre,
                'clienteTelefono' => $this->clienteTelefono,
                'mensaje' => $this->mensaje,
                'instanciaWS' => $instanciaWS,
                'apikey' => $apikey,
                'Base64' => $pdfBase64,
            ]);
            // Log::info('Evento NotificarClientePorWhatsappEvent despachado.');
        } catch (\Exception $e) {
            Log::error('Error en EnviarPdfComprobanteJob.', [
                'error' => $e->getMessage(),
                'stacktrace' => $e->getTraceAsString(),
            ]);
        }
    }
}
