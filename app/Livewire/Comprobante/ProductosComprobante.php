<?php

namespace App\Livewire\Comprobante;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\productoComprobante;
use App\Models\Comprobante;

class ProductosComprobante extends Component
{
    use WithPagination;


    public $comprobante;

    public function mount(Comprobante $idComprobante) 
    {
        // $this->post = Post::findOrFail($id);

        $this->comprobante = $idComprobante;

        // dd($this->comprobante);
    }


    public function render()
    {
        return view('livewire.comprobante.productos-comprobante',
        [
            'productos'=> productoComprobante::Where('comprobante_id',$this->comprobante->id)->paginate(30),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
