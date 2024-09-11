<?php

namespace App\Livewire\Factura;

use Livewire\Component;

use Livewire\Attributes\Modelable;


use App\Models\Cliente;


class Clientes extends Component
{

    public $empresa_id;

    #[Modelable] 
    public $clienteBuscado;

    public $resultados=[];


    // public function seleccionarCliente()
    // {
    //     $this->dispatch('seleccionarCliente', todoId: $this->todo->id); 
    // }

    public function busquedaDeCliente()
    {

        $this->resultados = Cliente::where('empresa_id',$this->empresa_id)
            ->whereAny(['numeroDocumento','razonSocial'], 'like', '%' . $this->clienteBuscado . '%')->limit(5)->get();
            
    }

    public function seleccionar($id,$razonSocial)
    {

        $this->clienteBuscado = $razonSocial;
        $this->resultados=[];

        $this->dispatch('seleccionarCliente', cliente: $id); 
        
    }


    public function render()
    {
        return view('livewire.factura.clientes');
    }
}
