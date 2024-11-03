<?php

namespace App\Livewire\Configuracion;

use Livewire\Component;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

use App\Models\Empresa;
use App\Models\FormaPago;



class Basico extends Component
{

    public $usuario;
    public $empresa;
    public $formaPago;
    //////////////valiables edicion//////////////
    public $idFormaPago;
    public $topeFacturacion;
    public $formatoImprecion;
    public $imprimirSiNo;
    public $domicilio;
    public $ivaDefecto;
    public $precio2;
    public $precio3;
    public $inicioActividades;
    public $telefono;
    public $correo;




        // "id" => 1
        // "razonSocial" => "Empresa Prueba"
        // "cuit" => 20080202874
        // "claveFiscal" => "sinclave"
        // "fe" => "no"
        // "iva" => "ME"
        // "titular" => "Empresa Prueba"
        // "logo" => null
        // "vencimientoPago" => null
    
        // PARA CONFIGURAR 
        // "idFormaPago" => 1
        // "pagoServicio" => 0
        // "topeFacturacion" => 0.0
        // "formatoImprecion" => "T"
        // "imprimirSiNo" => 1
        // "topeComprobantes" => 10
        // "domicilio" => "Av. del Trabajo 540 Las Lajas"
        // "ivaDefecto" => 21.0
        // "precio2" => 50.0
        // "precio3" => 100.0

        // "inicioActividades" => "2024-06-24"
        // "telefono" => "2994562062"
        // "correo" => "marce_nqn_19@hotmail.com"
        // "created_at" => null
        // "updated_at" => "2024-10-14 18:54:58"

    public function mount(){

        $this->usuario = Auth()->user();
        $this->empresa = Empresa::find(Auth()->user()->empresa_id);
        $this->formaPago = FormaPago::all();

        $this->idFormaPago = $this->empresa->idFormaPago;
        $this->topeFacturacion = $this->empresa->topeFacturacion;
        $this->formatoImprecion = $this->empresa->formatoImprecion;
        $this->imprimirSiNo = $this->empresa->imprimirSiNo;
        $this->domicilio = $this->empresa->domicilio;
        $this->ivaDefecto = $this->empresa->ivaDefecto;
        $this->precio2 = $this->empresa->precio2;
        $this->precio3 = $this->empresa->precio3;

        $this->inicioActividades = $this->empresa->inicioActividades;
        $this->telefono = $this->empresa->telefono;
        $this->correo = $this->empresa->correo;









        // dd($this->empresa);


    }

    public function guardarEmpresa(){

        $this->empresa->idFormaPago = $this->idFormaPago;
        $this->empresa->topeFacturacion = $this->topeFacturacion;
        $this->empresa->formatoImprecion = $this->formatoImprecion;
        $this->empresa->imprimirSiNo = $this->imprimirSiNo;
        $this->empresa->domicilio = $this->domicilio;
        $this->empresa->ivaDefecto = $this->ivaDefecto;
        $this->empresa->precio2 = $this->precio2;
        $this->empresa->precio3 = $this->precio3;
        $this->empresa->inicioActividades = $this->inicioActividades;

        $this->empresa->telefono = $this->telefono;
        $this->empresa->correo = $this->correo;








        $this->empresa->save();

        session()->flash('mensaje', 'Guardado Correcto.');
    }
     
    public function render()
    {
        return view('livewire.configuracion.basico')
        ->extends('layouts.app')
        ->section('main');
    }
}
