<?php

namespace App\Livewire\Mesas;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use App\Models\Comanda;


class Comandas extends Component
{

    public $modalImprimir = 'close';
    public $id=0;

    public function imprimir(Comanda $comanda){

        $this->id = $comanda->id;
        $this->modalImprimir = 'open'; 

        $comanda->estado = 'Impreso';
        $comanda->save();

    }

    public function borrar(Comanda $comanda){

        $comanda->delete();


    }

    public function borrarTodo(){

        $deleted = Comanda::where('empresa_id', Auth()->user()->empresa_id)->delete();

    }

    public function cerrarModal(){

        $this->modalImprimir = 'close'; 


    }

    public function render()
    {
        return view('livewire.mesas.comandas',[
            'comandas'=>Comanda::where('empresa_id',Auth()->user()->empresa_id)->orderBy('created_at','DESC')->get()
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
