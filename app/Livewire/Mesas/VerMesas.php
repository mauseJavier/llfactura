<?php

namespace App\Livewire\Mesas;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 

use App\Models\Mesa;
use App\Models\Sector;



class VerMesas extends Component
{

    public $buscarMesa,
        $nombreMesa,
        $numeroMesa,
        $capacidadMesa=1,
        $sectorMesa,
        
        $nombreSector,
        $numeroSector,
        $capacidadSector = 1;


    public function mount(){
        $sector= Sector::where('empresa_id',Auth()->user()->empresa_id)->first();

        // dd($sector->id);
        if($sector){

            $this->sectorMesa = $sector->id;
        }else{

            $this->sectorMesa = NULL;


        }

    }

    public function modificarMesa($mesa){

        
        $this->redirectRoute('modificarMesa', ['mesa' => $mesa]);


    }


    public function guardarMesa(){

        $validated = $this->validate([ 
            'nombreMesa' => 'required|min:1',
            'numeroMesa' => 'required|min:1|numeric',
            'capacidadMesa' => 'required',


        ], [
            'numeroMesa.required' => 'El campo Numero es obligatorio.',
            'numeroMesa.numeric' => 'El campo Numero debe ser un número.',
            'numeroMesa.min' => 'El campo Numero debe ser mayor que 1.',

            'nombreMesa.required' => 'El campo Nombre es obligatorio.',
            'nombreMesa.min' => 'El campo Nombre debe ser mayor que 1.',

            'capacidadMesa.required' => 'Seleccione Capacidad.',

        ]);


        $mesa = Mesa::create([
            'numero' => $this->numeroMesa,
            'nombre' => $this->nombreMesa,
            'capacidad' => $this->capacidadMesa,
            'sector' => $this->sectorMesa,
            'empresa_id' => Auth()->user()->empresa_id,

        ]);


        $this->numeroMesa='';
        $this->nombreMesa='';
        $this->capacidadMesa=1;
        $this->sectorMesa='';


        session()->flash('btnGuardar', 'Guardado!!');


    }


    public function guardarSector(){

        $validated = $this->validate([ 
            'nombreSector' => 'required|min:1',
            'numeroSector' => 'required|min:1|numeric',
            'capacidadSector' => 'required',


        ], [
            'numeroSector.required' => 'El campo Numero es obligatorio.',
            'numeroSector.numeric' => 'El campo Numero debe ser un número.',
            'numeroSector.min' => 'El campo Numero debe ser mayor que 1.',

            'nombreSector.required' => 'El campo Nombre es obligatorio.',
            'nombreSector.min' => 'El campo Nombre debe ser mayor que 1.',

            'capacidadSector.required' => 'Seleccione Capacidad.',

        ]);


        $mesa = Sector::create([
            'numero' => $this->numeroSector,
            'nombre' => $this->nombreSector,
            'capacidad' => $this->capacidadSector,
            'empresa_id' => Auth()->user()->empresa_id,

        ]);


        $this->numeroSector='';
        $this->nombreSector='';
        $this->capacidadSector=1;


        session()->flash('btnGuardar', 'Guardado!!');


    }


    public function render()
    {

        $mesas= Mesa::where('empresa_id',Auth()->user()->empresa_id)
        ->when($this->buscarMesa, function ($query, $buscarMesa) {
            return $query->whereAny([
                'nombre',
                'numero',
                'razonSocial',
            ], 'like', '%'. $buscarMesa.'%');
        })
        ->get();


        return view('livewire.mesas.ver-mesas',[
            'sector'=> Sector::where('empresa_id',Auth()->user()->empresa_id)->get(),
            'mesas'=> $mesas,
            'totalMesas'=> count($mesas),
            'mesaOcupada'=> $mesas->filter(fn($mesa) => $mesa->data)->count() ,


        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
