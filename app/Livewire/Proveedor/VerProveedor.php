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



        // $proveedor = Proveedor::updateOrCreate(
        //     ['cuit'=>$this->cuit,'empresa_id'=> Auth::user()->empresa_id],
        //     [            
        //     'nombre'=>trim($this->nombre),
        //     ]
        // );

                // Verificar si ya existe un Proveedor con el mismo nombre y empresa_id
                $existe = Proveedor::whereRaw('LOWER(nombre) = ?', [strtolower($this->nombre)])
                ->where('empresa_id', Auth::user()->empresa_id)
                ->exists();
            
        
                if ($existe) {
                // Si ya existe, puedes lanzar una excepción, retornar un mensaje de error, etc.
                    // throw new \Exception('El Proveedor ya existe para esta empresa.');
                    session()->flash('mensaje', 'Proveedor "'.$this->nombre.'" Ya Existe');
        
                } else {
                        // Si no existe, crear el nuevo rubro
                        $nuevoProveedor = Proveedor::create([
                            'nombre'=> $this->nombre,
                            'empresa_id'=>Auth::user()->empresa_id,
                        ]);
        
                    if($nuevoProveedor){
            
                        session()->flash('mensaje', 'Proveedor "'.$this->nombre.'" Guardado.');
                        $this->redirect('/proveedor');
            
                    }else{
                        session()->flash('mensaje', 'Ocurrio un error');
            
                    }
                }

                $this->nombre = '';
                $this->cuit = '';


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
