<?php

namespace App\Listeners;

use App\Events\DescontarStockEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Stock;

class DescontarStockListener
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
    public function handle(DescontarStockEvent $event): void
    {
        Stock::create([
            'codigo'=> $event->codigo,
            'detalle'=> $event->detalle,
            'deposito_id'=> $event->deposito_id,
            'stock'=> $event->stock,
            'comentario'=> $event->comentario,
            'usuario'=> $event->usuario,
            'empresa_id'=> $event->empresa_id,
        ]);
    }
}
