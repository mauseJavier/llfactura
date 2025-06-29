<?php

namespace App\Listeners;

use App\Events\NotificarClientePorCorreoEvent;
use App\Mail\NotificarClientePorCorreo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoAlCliente implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(NotificarClientePorCorreoEvent $event): void
    {
        Mail::to($event->cliente->correo)
            ->send(new NotificarClientePorCorreo($event->cliente, $event->asunto, $event->mensaje));
    }
}
