<?php

namespace App\Livewire\Reportes;

use Livewire\Component;

use Livewire\Attributes\Url;


class Reportes extends Component
{

    #[Url]
    public $ruta = ''; // Will be set to "bob"...



    public function render()
    {
        return view('livewire.reportes.reportes',[
            'ruta'=>$this->ruta
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
