<?php

namespace App\Livewire\VerOrdenCompra;

use Livewire\Component;

use App\Models\OrdenCompra;
use App\Models\ArticuloOrden;
// use App\Models\Inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class HistorialOrdenCompra extends Component
{
    use WithPagination;

    // eliminar orden compra
    public function eliminarOrdenCompra($id)
    {
        $ordenCompra = OrdenCompra::where('id',$id)->where('empresa_id', Auth::user()->empresa_id)->get()->first();
        if ($ordenCompra) {
            $ordenCompra->delete();
            session()->flash('mensaje', 'Orden de compra eliminada correctamente.');
        } else {
            session()->flash('error', 'Orden de compra no encontrada.');
        }
    }


    public function render()
    {
        return view('livewire.ver-orden-compra.historial-orden-compra',[
            'ordenes' => OrdenCompra::with('articulos')->where('empresa_id', Auth::user()->empresa_id)->get(),
            // 'articulos' => ArticuloOrden::with('articulo')->where('orden_compra_id', 1)->get(),
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
