<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarComprobantePorCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public $cliente;
    public $mensaje;
    public $pdfContent;
    public $nombreArchivo;

    /**
     * Create a new message instance.
     */
    public function __construct($cliente, $mensaje, $pdfContent, $nombreArchivo)
    {
        $this->cliente = $cliente;
        $this->mensaje = $mensaje;
        $this->pdfContent = $pdfContent;
        $this->nombreArchivo = $nombreArchivo;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Comprobante de compra')
            ->view('emails.comprobante-compra')
            ->with([
                'cliente' => $this->cliente,
                'mensaje' => $this->mensaje,
            ])
            ->attachData($this->pdfContent, $this->nombreArchivo, [
                'mime' => 'application/pdf',
            ]);
    }
}
