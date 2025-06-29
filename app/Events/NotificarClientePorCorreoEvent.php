<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificarClientePorCorreoEvent
{
    use Dispatchable, SerializesModels;

    public $cliente;
    public $asunto;
    public $mensaje;

    public function __construct($cliente, $asunto, $mensaje)
    {
        $this->cliente = $cliente;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
    }
}
