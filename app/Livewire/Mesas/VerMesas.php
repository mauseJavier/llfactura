<?php

namespace App\Livewire\Mesas;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

use App\Models\Mesa;
use App\Models\Sector;



class VerMesas extends Component
{

    public $buscarMesa;


    public function modificarMesa($mesa){

        
        $this->redirectRoute('modificarMesa', ['mesa' => $mesa]);


    }

    public function render()
    {
        return view('livewire.mesas.ver-mesas',[
            'sector'=> Sector::where('empresa_id',Auth()->user()->empresa_id)->get(),
            'mesas'=> Mesa::where('empresa_id',Auth()->user()->empresa_id)
                ->when($this->buscarMesa, function ($query, $buscarMesa) {
                    return $query->where('nombre','LIKE', '%'. $buscarMesa.'%');
                })
                ->get(),

        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
