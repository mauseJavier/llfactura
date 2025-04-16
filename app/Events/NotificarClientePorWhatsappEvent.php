<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificarClientePorWhatsappEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    // NotificarClientePorWhatsappEvent::dispatch([
    //     'clienteNombre' => $this->cliente->nombre,
    //     'clienteTelefono' => $this->cliente->telefono,
    //     'mensaje' => $mensaje,
    //     'instanciaWS' => $instanciaWS,
    //     'apikey' => $apikey,
    //     // 'tokenTelegram' => $tokenTelegram,
    // ]);

    public $clienteNombre;
    public $clienteTelefono;
    public $mensaje;
    public $instanciaWS;
    public $apikey;
    public $Base64;

    public function __construct($datos)
    {
        $this->clienteNombre = $datos['clienteNombre'];
        $this->clienteTelefono = $datos['clienteTelefono'];
        $this->mensaje = $datos['mensaje'];
        $this->instanciaWS = $datos['instanciaWS'];
        $this->apikey = $datos['apikey'];
        $this->Base64 = $datos['Base64'];
        // $this->tokenTelegram = $datos['tokenTelegram'];

        // logger()->info("cliente nombre: {$datos['clienteNombre']}");    
        // logger()->info("cliente telefono: {$datos['clienteTelefono']}");
        // logger()->info("mensaje: {$datos['mensaje']}");
        // logger()->info("instanciaWS: {$datos['instanciaWS']}");
        // logger()->info("apikey: {$datos['apikey']}");
        // logger()->info("tokenTelegram: {$datos['tokenTelegram']}");

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
