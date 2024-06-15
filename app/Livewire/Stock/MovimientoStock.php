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

    public function mount(){


        $this->empresa = Empresa::find(Auth::user()->empresa_id);

    }
    public function render()
    {
        return view('livewire.stock.movimiento-stock',[
            'registros'=> DB::table('stocks as a')
                ->select('a.*', 'b.nombre as nombreDeposito')
                ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
                ->where('a.codigo', 'like', '%' . $this->codigo . '%')
                ->where('a.empresa_id', $this->empresa->id)
                ->paginate(10),
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}


// DB::select('SELECT a.*,b.nombre as nombreDeposito 
// from stocks a, depositos b
// WHERE a.deposito_id = b.id and a.codigo like "%'.$this->codigo.'%" AND 
// a.empresa_id = '.$this->empresa->id.'')->paginate(3)