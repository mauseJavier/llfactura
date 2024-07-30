<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaldoCuentaCorriente
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $empresa_id;
    public $cliente_id;
    public $comprobante_id;
    public $tipo;
    public $comentario;
    public $debe;
    public $haber;
    // public $saldo;

    /**
     * Create a new event instance.
     */
    public function __construct($datos)
    {
        $this->empresa_id = $datos['empresa_id'];
        $this->cliente_id = $datos['cliente_id'];
        $this->comprobante_id = $datos['comprobante_id'];
        $this->tipo = $datos['tipo'];
        $this->comentario = $datos['comentario'];
        $this->debe = $datos['debe'];
        $this->haber = $datos['haber'];
        // $this->saldo = $datos['saldo']; se calcula en el listener
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
