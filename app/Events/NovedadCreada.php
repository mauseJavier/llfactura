<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NovedadCreada
{
    use Dispatchable, SerializesModels;

    public $titulo;
    public $detalle;
    public $nombreRuta;
    public $url;
    public $pie;
    public $usuario;
    public $empresa_id;

    public function __construct($titulo, $detalle, $nombreRuta, $url, $pie, $usuario, $empresa_id)
    {
        $this->titulo = $titulo;
        $this->detalle = $detalle;
        $this->nombreRuta = $nombreRuta;
        $this->url = $url;
        $this->pie = $pie;
        $this->usuario = $usuario;
        $this->empresa_id = $empresa_id;
    }
}

