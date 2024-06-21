<?php

namespace App\Livewire\Cliente;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

use App\Models\Empresa;

use App\Models\Cliente;


class VerCliente extends Component
{

    public $cuit;   
    public $razonSocial;    
    public $tipoDocumento=99;  
    public $domicilio;  
    public $correoCliente;  
    public $tipoContribuyente=5;  

    public $datoBuscado;


    public function editarCliente(Cliente $cliente){
        // dd($cliente);

        $this->cuit = $cliente->numeroDocumento;
        $this->razonSocial = $cliente->razonSocial;
        $this->tipoDocumento = $cliente->tipoDocumento;
        $this->domicilio = $cliente->domicilio;
        $this->correoCliente = $cliente->correoCliente;
        $this->tipoContribuyente = $cliente->tipoContribuyente;



    }

    public function guardarCliente(){

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



        $cliente = Cliente::updateOrCreate(
            ['numeroDocumento'=>$this->cuit,'empresa_id'=> Auth::user()->empresa_id],
            [            
            'tipoDocumento'=>trim($this->tipoDocumento),

            'razonSocial'=>trim($this->razonSocial),
            'domicilio'=>trim($this->domicilio),
            'correo'=>trim($this->correoCliente),
            'tipoContribuyente'=>trim($this->tipoContribuyente)]
        );

        session()->flash('mensaje', 'Cliente '.$cliente->razonSocial.' Guardado.');

    }
    public function render()
    {
        return view('livewire.cliente.ver-cliente',[
            'clientes'=> Cliente::where('empresa_id', Auth::user()->empresa_id)
                                ->whereAny([
                                    'numeroDocumento',
                                    'razonSocial',
                                    'domicilio'
                                ], 'LIKE', '%'.$this->datoBuscado.'%')->orderby('created_at','DESC')->get(),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
