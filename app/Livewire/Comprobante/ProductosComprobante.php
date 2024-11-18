<?php

namespace App\Livewire\Comprobante;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\productoComprobante;
use App\Models\Comprobante;
use App\Models\Deposito;
use App\Models\FormaPago;



class ProductosComprobante extends Component
{
    use WithPagination;


    public $comp;

    public function mount(Comprobante $idComprobante) 
    {
        // $this->post = Post::findOrFail($id);

        $this->comp = $idComprobante;

        // dd($this->comprobante);
    }


    public function render()
    {
        return view('livewire.comprobante.productos-comprobante',
        [
            'productos'=> productoComprobante::Where('comprobante_id',$this->comp->id)->paginate(30),
            'depo'=> Deposito::find($this->comp->deposito_id),

            'fpUno'=> FormaPago::select('nombre')->find($this->comp->idFormaPago),
            'fpDos'=> FormaPago::select('nombre')->find($this->comp->idFormaPago2),



        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
