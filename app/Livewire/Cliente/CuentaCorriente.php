<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithPagination;

use App\Events\SaldoCuentaCorriente;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Empresa;

use App\Models\Cliente;
// use App\Models\CuentaCorriente;

class CuentaCorriente extends Component
{

    use WithPagination;


    public $usuario;
    public $cliente;
    // public $saldo;

    public $comentario;
    public $importePagado;


    public $fechaDesde;

    public function mount(Cliente $cliente){

        $this->cliente = $cliente;

        if($this->cliente->empresa_id != Auth::user()->empresa_id){

            session()->flash('mensaje', 'Usuario Incorrecto.');
 
            return $this->redirect('/cliente');
        }

        // $this->movimientos = DB::table('cuenta_corrientes')->where('cliente_id',$cliente->id)->orderBy('created_at','DESC')->paginate(5);
        // $this->saldo = DB::table('cuenta_corrientes')->select('saldo')->where('cliente_id',$cliente->id)->orderBy('created_at','DESC')->limit(1)->get();

        $this->fechaDesde = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end = Carbon::now();

        $this->usuario= Auth::user();

    }

    public function pagar(){

        $validated = $this->validate([ 
            'comentario' => 'required|min:1',
            'importePagado' => 'required|min:1|numeric',
        ]);

        //AK APLICA EL SALDO AL CLIENTE
        SaldoCuentaCorriente::dispatch([
        'empresa_id'=>$this->usuario->empresa_id,
        'cliente_id'=>$this->cliente->id,
        'comprobante_id'=>0,
        'tipo'=>'pago',
        'comentario'=>$this->comentario,
        'debe'=>0,
        'haber'=>round($this->importePagado,2),
        'usuario'=> $this->usuario->name,
        // 'saldo'=>round($this->total,2), el saldo se calcula en listener 

        ]);

        $this->comentario='';
        $this->importePagado=0;


    }


    public function render()
    {
        return view('livewire.cliente.cuenta-corriente',[
            'movimientos'=> DB::table('cuenta_corrientes')
                ->where('cliente_id',$this->cliente->id)
                ->where('created_at','>=',$this->fechaDesde)
                ->orderBy('created_at','DESC')->paginate(5),
            'saldo' => DB::table('cuenta_corrientes')->select('saldo')->where('cliente_id',$this->cliente->id)->orderBy('created_at','DESC')->limit(1)->get(),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
