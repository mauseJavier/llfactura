<?php

namespace App\Livewire\Mesas;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use App\Models\Comanda;


class Comandas extends Component
{

    public $id=0;

    public function cambioEstado(Comanda $comanda){



        $comanda->estado = 'Impreso';
        $comanda->save();

    }

    public function borrar(Comanda $comanda){

        $comanda->delete();


    }

    public function borrarTodo(){

        $deleted = Comanda::where('empresa_id', Auth()->user()->empresa_id)->delete();

    }


    public function render()
    {
        $empresaId = auth()->user()->empresa_id;
    
        $comandas = Comanda::with('mesa')
                           ->where('empresa_id', $empresaId)
                           ->orderBy('created_at', 'DESC')
                           ->get();

        // dd($comandas->first());
    
        return view('livewire.mesas.comandas', [
            'comandas' => $comandas,
        ])
        ->extends('layouts.app')
        ->section('main');
    }
    
}
