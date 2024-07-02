<?php

namespace App\Livewire\Stock;

use Livewire\Component;
use Livewire\Attributes\Url;

use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventario;
use App\Models\Stock;
use App\Models\Empresa;
use App\Models\Deposito;

class MovimientoStock extends Component
{
    use WithPagination;

    public $codigo;
    public $empresa;

    public $depositoId;

    public function mount(){


        $this->empresa = Empresa::find(Auth::user()->empresa_id);
        $this->depositoId = 'todo';


    }
    public function render()
    {
        return view('livewire.stock.movimiento-stock',[
            'registros'=> DB::table('stocks as a')
            ->select('a.*', 'b.nombre as nombreDeposito')
            ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
            ->where('a.codigo', 'like', '%' . $this->codigo . '%')
            ->where('a.empresa_id', $this->empresa->id)
            ->when($this->depositoId !== 'todo', function ($query) {
                return $query->where('a.deposito_id', $this->depositoId);
            })
            ->paginate(10),
            'depositos'=> Deposito::where('empresa_id',$this->empresa->id)->get(),
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}


// DB::select('SELECT a.*,b.nombre as nombreDeposito 
// from stocks a, depositos b
// WHERE a.deposito_id = b.id and a.codigo like "%'.$this->codigo.'%" AND 
// a.empresa_id = '.$this->empresa->id.'')->paginate(3)