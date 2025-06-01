<?php

namespace App\Livewire\Mesas;

use Livewire\Component;




class TarjetaMesas extends Component
{

    public $mesas;
    public $idSector;

    // public function mount($sector,$mesas)
    // {
    //     // Initialization code can go here

    //     $this->sector = $sector;
    //     $this->mesas = $mesas;
    // }

    public function modificarMesa($id)
    {
        $this->dispatch('modificarMesa', mesa: $id); 
    }


    public function render()
    {
        return view('livewire.mesas.tarjeta-mesas', [

        ]);
    }



}
