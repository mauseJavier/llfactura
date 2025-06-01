<?php

namespace App\Livewire\Inventario;

use Livewire\Component;


class GaleriaImagen extends Component
{

    public $imagenes = [];


    public function mount($imagenes)
    {
        
        $this->imagenes = $imagenes;


        // dd(json_decode ($this->imagen, true));
        
    }
    


    public function render()
    {
        return view('livewire.inventario.galeria-imagen');
    }
}
