<?php

namespace App\Livewire\Presupuesto;

use Livewire\Component;


use Illuminate\Support\Facades\Auth;

use App\Models\Presupuesto;
use App\Models\ProductoPresupuesto;


class VerPresupuesto extends Component
{

    public $presupuesto;
    public $total =0 ;

    public $datoBuscado;

    public function traerProductos(Presupuesto $presupuesto){
        // dd($presupuesto);

        $this->presupuesto = $presupuesto;
        $this->total =$this->presupuesto->total;

    }


    public function mount(){

        $this->presupuesto = Presupuesto::where('empresa_id',Auth::user()->empresa_id)->first();
        $this->total = $this->presupuesto->total ? $this->presupuesto->total : 0;    

    }

    public function render()
    {
        return view('livewire.presupuesto.ver-presupuesto',[
            'presupuestos'=> Presupuesto::where('empresa_id',Auth::user()->empresa_id)
                                        ->whereAny([
                                            'numero',
                                            'razonSocial',
                                            'usuario'
                                        ], 'LIKE', '%'.$this->datoBuscado.'%')->orderby('created_at','DESC')->get(),
            'productos'=> ProductoPresupuesto::where('presupuesto_id',$this->presupuesto->id)->get(),


        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
