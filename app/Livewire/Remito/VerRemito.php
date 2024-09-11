<?php

namespace App\Livewire\Remito;

use Livewire\Component;

class VerRemito extends Component
{
    public function render()
    {
        return view('livewire.remito.ver-remito')
        ->extends('layouts.app')
        ->section('main'); 
    }
}
