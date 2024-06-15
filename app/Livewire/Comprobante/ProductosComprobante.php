<?php

namespace App\Livewire\Comprobante;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\productoComprobante;

class ProductosComprobante extends Component
{
    use WithPagination;

    public $idComprobante;

    public function mount($idComprobante) 
    {
        // $this->post = Post::findOrFail($id);

        $this->idComprobante = $idComprobante;
    }


    public function render()
    {
        return view('livewire.comprobante.productos-comprobante',
        [
            'productos'=> productoComprobante::Where('comprobante_id',$this->idComprobante)->paginate(30),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
