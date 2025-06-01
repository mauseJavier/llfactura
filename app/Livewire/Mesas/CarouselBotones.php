<?php

namespace App\Livewire\Mesas;

use Livewire\Component;

class CarouselBotones extends Component
{

    public $rubros;

    // public function filtroRubro($rubro)
    // {
    //     $this->dispatch('filtroRubro', rubro: $rubro); 
    // }




    public function render()
    {
        return view('livewire.mesas.carousel-botones');
    }
}
