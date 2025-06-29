<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificarClientePorCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $mensaje;
    public $cliente;

    /**
     * Create a new message instance.
     */
    public function __construct($cliente, $asunto, $mensaje)
    {
        $this->cliente = $cliente;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->asunto)
            ->view('emails.notificar-cliente')
            ->with([
                'cliente' => $this->cliente,
                'mensaje' => $this->mensaje,
            ]);
    }
}
