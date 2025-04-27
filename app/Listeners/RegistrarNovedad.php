<?php

namespace App\Listeners;

use App\Events\NovedadCreada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Novedad;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class RegistrarNovedad
{
    public function __construct()
    {
        //
    }

    public function handle(NovedadCreada $event)
    {
        // Validar los datos de la novedad
        $validator = Validator::make([
            'detalle' => $event->detalle,
            'titulo' => $event->titulo ?? 'Nueva Novedad',
            'nombreRuta' => $event->nombreRuta ?? null,
            'url' => $event->url ?? null,
            'pie' => $event->pie ?? null,
            'usuario' => $event->usuario ?? 'Sistema',
            'empresa_id' => $event->empresa_id ?? 1,
        ], [
            'titulo' => 'required|string|max:255',
            'detalle' => 'required|string|max:1000',
            'nombreRuta' => 'nullable|string|max:255',
            'url' => 'nullable|url|max:255',
            'pie' => 'nullable|string|max:255',
            'usuario' => 'required|string|max:255',
            'empresa_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            \Log::error('Error al validar la novedad: ' . $validator->errors());
            return;
        }

        // Guardar la novedad en la base de datos
        Novedad::create([
            'titulo' => $event->titulo,
            'detalle' => $event->detalle,
            'nombreRuta' => $event->nombreRuta,
            'url' => $event->url,
            'pie' => $event->pie,
            'usuario' => $event->usuario,
            'empresa_id' => $event->empresa_id,

        ]);

        \Log::info('Novedad registrada: ' . $event->detalle);
    }
}