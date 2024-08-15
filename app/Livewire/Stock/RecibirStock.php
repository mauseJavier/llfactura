<?php

namespace App\Livewire\Stock;

use Livewire\Component;

use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventario;
use App\Models\Stock;
use App\Models\Empresa;
use App\Models\Deposito;
use App\Models\EnvioStock;

class RecibirStock extends Component
{

    use WithPagination;

    public $nombreDepositoUsuario;


    public function recibirStock(EnvioStock $registro){

// RECIBIMOS EL STOCK 
        Stock::create([
            'codigo'=> $registro->codigo,
            'detalle'=> $registro->detalle,
            'deposito_id'=> $registro->depositoDestino_id,
            'stock'=> $registro->stock ,
            'comentario'=>'Recepcion stock',
            'usuario'=>Auth::user()->name,
            'empresa_id'=>Auth::user()->empresa_id,
        ]);

// ACTUALIZAMOS EL REGISTRO 
        $registro->usuarioRecibo = Auth::user()->name;
        $registro->estado = 'recibido';
        $registro->save();

        session()->flash('mensaje', 'Recibido.');
    }

    public function mount(){

        $this->nombreDepositoUsuario = Deposito::where('id',Auth::user()->deposito_id)->get();
        
    }
    public function render()
    {
        return view('livewire.stock.recibir-stock',[
            'registros'=> EnvioStock::select([
                'envio_stocks.id',
                'envio_stocks.codigo',
                'envio_stocks.detalle',
                'envio_stocks.stock',
                'envio_stocks.estado',
                DB::raw('(SELECT nombre FROM depositos WHERE id = envio_stocks.depositoOrigen_id) AS depositoOrigen'),
                DB::raw('(SELECT nombre FROM depositos WHERE id = envio_stocks.depositoDestino_id) AS depositoDestino'),
                'envio_stocks.comentario',
                'envio_stocks.usuarioEnvio',
                'envio_stocks.empresa_id',
                'envio_stocks.created_at AS fechaHora',
            ])
            ->where('envio_stocks.empresa_id', Auth::user()->empresa_id)
            ->where('envio_stocks.estado', 'enviado')
            ->where('envio_stocks.depositoDestino_id', Auth::user()->deposito_id)
            ->orderBy('envio_stocks.created_at')
            ->paginate(10),
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
