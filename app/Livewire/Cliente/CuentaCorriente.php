<?php

namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithPagination;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Empresa;

use App\Models\Cliente;
// use App\Models\CuentaCorriente;

class CuentaCorriente extends Component
{

    use WithPagination;


    public $cliente;
    public $saldo;

    public $fechaDesde;

    public function mount(Cliente $cliente){

        $this->cliente = $cliente;
        $this->movimientos = DB::table('cuenta_corrientes')->where('cliente_id',$cliente->id)->orderBy('created_at','DESC')->paginate(5);
        $this->saldo = DB::table('cuenta_corrientes')->select('saldo')->where('cliente_id',$cliente->id)->orderBy('created_at','DESC')->limit(1)->get();

        $this->fechaDesde = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end = Carbon::now();

    }
    public function render()
    {
        return view('livewire.cliente.cuenta-corriente',[
            'movimientos'=> DB::table('cuenta_corrientes')->where('cliente_id',$this->cliente->id)->orderBy('created_at','DESC')->paginate(5),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
