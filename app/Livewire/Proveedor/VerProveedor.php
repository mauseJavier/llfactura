<?php

namespace App\Livewire\Proveedor;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

use App\Models\Empresa;

use App\Models\Proveedor;



class VerProveedor extends Component
{

    public $datoBuscado;

    public $nombre;
    public $cuit;


    public function guardarProveedor(){

        $validated = $this->validate([
            'cuit' => 'required|numeric|min:1',
            'nombre' => 'required|min:1',
        ], [
            'cuit.required' => 'El campo CUIT a enviar es obligatorio.',
            'cuit.numeric' => 'El campo CUIT a enviar debe ser un número.',
            'cuit.min' => 'El campo CUIT a enviar debe ser mayor que 0.',

            'nombre.required' => 'El campo Razon Social a enviar es obligatorio.',
            'nombre.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);



        $proveedor = Proveedor::updateOrCreate(
            ['cuit'=>$this->cuit,'empresa_id'=> Auth::user()->empresa_id],
            [            
            'nombre'=>trim($this->nombre),
            ]
        );

        session()->flash('mensaje', 'Proveedor '.$proveedor->nombre.' Guardado.');

    }

    public function editarProveedor(Proveedor $proveedor){
        
        $this->nombre = $proveedor->nombre;
        $this->cuit = $proveedor->cuit;
    }
    public function render()
    {
        return view('livewire.proveedor.ver-proveedor',[
            'proveedores'=>Proveedor::where('empresa_id',Auth::user()->empresa_id)
                                ->whereAny([
                                    'nombre',
                                    'cuit'
                                ],'LIKE','%'.$this->datoBuscado.'%')                        
                                ->orderBy('created_at','DESC')->get(),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
