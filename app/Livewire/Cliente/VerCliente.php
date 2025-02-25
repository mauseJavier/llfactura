<?php

namespace App\Livewire\Cliente;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Models\Empresa;

use App\Models\Cliente;


class VerCliente extends Component
{

    public $idCliente;
    public $cuit;   
    public $razonSocial;    
    public $tipoDocumento=99;  
    public $domicilio;  
    public $correoCliente;  
    public $tipoContribuyente=5;  

    public $datoBuscado;


    public function editarCliente(Cliente $cliente){
        // dd($cliente);

        $this->idCliente = $cliente->id;

        $this->cuit = $cliente->numeroDocumento;
        $this->razonSocial = $cliente->razonSocial;
        $this->tipoDocumento = $cliente->tipoDocumento;
        $this->domicilio = $cliente->domicilio;
        $this->correoCliente = $cliente->correoCliente;
        $this->tipoContribuyente = $cliente->tipoContribuyente;



    }

    public function cancelar(){

        $this->idCliente = '';

        $this->cuit = '';
        $this->razonSocial = '';
        $this->tipoDocumento = 99;
        $this->domicilio = '';
        $this->correoCliente = '';
        $this->tipoContribuyente = 5;
    

    }

    public function updateCliente(){

        $validated = $this->validate([
            'cuit' => 'required|numeric|min:1',
            'razonSocial' => 'required|min:1',
        ], [
            'cuit.required' => 'El campo CUIT a enviar es obligatorio.',
            'cuit.numeric' => 'El campo CUIT a enviar debe ser un número.',
            'cuit.min' => 'El campo CUIT a enviar debe ser mayor que 0.',

            'razonSocial.required' => 'El campo Razon Social a enviar es obligatorio.',
            'razonSocial.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);


        $cliente = Cliente::where('numeroDocumento', $this->cuit)
                    ->where('empresa_id',Auth::user()->empresa_id)
                    ->where('id','!=',$this->idCliente)
                    ->get();

                    // dd($cliente);


        if ($cliente->isEmpty()) {

            // dd('no cuit');
            Cliente::where('id', $this->idCliente)
                    ->update(
                        [
            
                            'numeroDocumento'=>$this->cuit,
                        
                            'tipoDocumento'=>trim($this->tipoDocumento),
                
                            'razonSocial'=>trim($this->razonSocial),
                            'domicilio'=>trim($this->domicilio),
                            'correo'=>trim($this->correoCliente),
                            'tipoContribuyente'=>trim($this->tipoContribuyente)
                        ]
                    );
    
    
    
            $this->idCliente='';
    
            $this->cuit='';
            $this->tipoDocumento=99;
            $this->razonSocial='';
            $this->domicilio='';
            $this->correoCliente='';
            $this->tipoContribuyente=5;
    
            session()->flash('guardado', 'Cliente Editado.');
            
        }else {
                
    
            $this->idCliente='';
    
            $this->cuit='';
            $this->tipoDocumento=99;
            $this->razonSocial='';
            $this->domicilio='';
            $this->correoCliente='';
            $this->tipoContribuyente=5;
    
            session()->flash('guardado', 'Cliente NO editado, Exite otro cliente con el mismo cuit/dni.');

        }


    }

    public function guardarCliente(){

        $validated = $this->validate([
            'cuit' => 'required|numeric|min:1',
            'razonSocial' => 'required|min:1',
            'tipoDocumento' => 'required',

        ], [
            'cuit.required' => 'El campo CUIT a enviar es obligatorio.',
            'cuit.numeric' => 'El campo CUIT a enviar debe ser un número.',
            'cuit.min' => 'El campo CUIT a enviar debe ser mayor que 0.',

            'razonSocial.required' => 'El campo Razon Social a enviar es obligatorio.',
            'razonSocial.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);


        $cliente = Cliente::where('numeroDocumento', $this->cuit)
                    ->where('empresa_id',Auth::user()->empresa_id)
                    ->get();

        // dd($cliente);

        if ($cliente->isEmpty()) {
            # code...
            $cliente = Cliente::create(
                [
    
                    'numeroDocumento'=>$this->cuit,
                    'empresa_id'=> Auth::user()->empresa_id,
                
                    'tipoDocumento'=>trim($this->tipoDocumento),
        
                    'razonSocial'=>trim($this->razonSocial),
                    'domicilio'=>trim($this->domicilio),
                    'correo'=>trim($this->correoCliente),
                    'tipoContribuyente'=>trim($this->tipoContribuyente)
                ]
            );
    
            $this->cuit='';
            $this->tipoDocumento=99;
            $this->razonSocial='';
            $this->domicilio='';
            $this->correoCliente='';
            $this->tipoContribuyente=5;
    
            session()->flash('guardado', 'Cliente '.$cliente->razonSocial.' Guardado.');
        } else {
            # code...
    
            $this->cuit='';
            $this->tipoDocumento=99;
            $this->razonSocial='';
            $this->domicilio='';
            $this->correoCliente='';
            $this->tipoContribuyente=5;
    
            session()->flash('guardado', 'Cliente NO Guardado. Existe otro cliente con el mismo CUIT/DNI');
        }
        


    }
    public function render()
    {
        return view('livewire.cliente.ver-cliente',[

            'clientes'=> DB::table('clientes')
                ->leftJoin('cuenta_corrientes', function ($join) {
                    $join->on('clientes.id', '=', 'cuenta_corrientes.cliente_id')
                        ->whereRaw('cuenta_corrientes.created_at = (select max(created_at) from cuenta_corrientes where cliente_id = clientes.id)');
                })
                ->where('clientes.empresa_id', Auth::user()->empresa_id)
                ->where(function ($query) {
                    $query->where('clientes.numeroDocumento', 'LIKE', '%'.$this->datoBuscado.'%')
                        ->orWhere('clientes.razonSocial', 'LIKE', '%'.$this->datoBuscado.'%')
                        ->orWhere('clientes.domicilio', 'LIKE', '%'.$this->datoBuscado.'%');
                })
                ->select('clientes.*', 'cuenta_corrientes.saldo')
                ->orderBy('clientes.created_at', 'DESC')
                ->get(),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
