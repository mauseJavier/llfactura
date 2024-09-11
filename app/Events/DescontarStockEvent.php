<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DescontarStockEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $codigo;
    public $detalle;
    public $deposito_id;
    public $stock;
    public $comentario;
    public $usuario;
    public $empresa_id;

    /**
     * Create a new event instance.
     */
    public function __construct($datos)
    {
        $this->codigo = $datos['codigo'];
        $this->detalle = $datos['detalle'];
        $this->deposito_id = $datos['deposito_id'];
        $this->stock = $datos['stock'];
        $this->comentario = $datos['comentario'];
        $this->usuario = $datos['usuario'];
        $this->empresa_id = $datos['empresa_id'];
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
