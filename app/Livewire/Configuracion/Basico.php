<?php

namespace App\Livewire\Configuracion;

use Livewire\Component;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\User;




class Basico extends Component
{

    public $usuario;
    public $empresa;
    public $formaPago;
    public $buscarUsuario;

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

    public $mesas;

    public $activarPago2;
    public $facturaDefault;

    public $ingresosBrutos;
    public $telefonoNotificacion;
    public $instanciaWhatsapp;
    public $tokenWhatsapp;
    public $ivaIncluido;





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

        $this->activarPago2 = $this->empresa->activarPago2;
        $this->facturaDefault = $this->empresa->facturaDefault;

        $this->mesas = $this->empresa->mesas;

        $this->ingresosBrutos = $this->empresa->ingresosBrutos;
        $this->telefonoNotificacion = $this->empresa->telefonoNotificacion;
        $this->instanciaWhatsapp = $this->empresa->instanciaWhatsapp;
        $this->tokenWhatsapp = $this->empresa->tokenWhatsapp;
        $this->ivaIncluido = $this->empresa->ivaIncluido;










        // dd($this->empresa);


    }

    public function eliminarUsuario(User $usuario){

        $usuario->delete();

        session()->flash('mensaje', 'Eliminado: '. $usuario->name .' Correo: '. $usuario->email);

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

        $this->empresa->activarPago2 = $this->activarPago2;
        $this->empresa->facturaDefault = $this->facturaDefault;

        $this->empresa->mesas = $this->mesas;

        $this->empresa->ingresosBrutos = $this->ingresosBrutos;
        $this->empresa->telefonoNotificacion = $this->telefonoNotificacion;
        $this->empresa->instanciaWhatsapp = $this->instanciaWhatsapp;
        $this->empresa->tokenWhatsapp = $this->tokenWhatsapp;
        $this->empresa->ivaIncluido = $this->ivaIncluido;

        $this->empresa->save();

        session()->flash('mensaje', 'Guardado Correcto.');

        $this->render();
    }
     
    public function render()
    {
        return view('livewire.configuracion.basico',[
            'usuariosEmpresa'=> DB::select("SELECT
                                        a.id AS usuarioId,
                                        c.nombre AS rol,
                                        a.*,
                                        b.*,
                                        c.*,
                                        d.nombre as nombreDeposito
                                    FROM
                                        users a,
                                        empresas b,
                                        roles c,
                                        depositos d
                                    WHERE
                                        a.empresa_id = b.id AND a.role_id = c.id  AND d.id = a.deposito_id AND
                                        (
                                            a.name LIKE '%$this->buscarUsuario%' OR
                                            a.email LIKE '%$this->buscarUsuario%' OR 
                                            b.razonSocial LIKE '%$this->buscarUsuario%' OR
                                            c.nombre LIKE '%$this->buscarUsuario%'
                                        )
                                        AND 
                                        a.empresa_id = ".$this->empresa->id."
                                    ORDER BY a.last_login DESC"
                                    ),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
