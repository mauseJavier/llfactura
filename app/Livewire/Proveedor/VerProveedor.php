<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

use App\Models\Empresa;

use App\Models\Proveedor;



class VerProveedor extends Component
{
    public function render()
    {
        return view('livewire.proveedor.ver-proveedor',[
            'proveedores'=>Proveedor::where('empresa_id',Auth::user()->empresa_id)->get(),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
