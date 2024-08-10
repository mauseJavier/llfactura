<?php

namespace App\Livewire\Stock;

use Livewire\Component;

use Carbon\Carbon;

use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\EnvioStock;
use App\Models\Stock;

class HistoricoEnvio extends Component
{
    use WithPagination;


    public $fechaFiltro;

    public function mount(){
        $this->fechaFiltro = Carbon::now()->format('Y-m-d');
    }

    public function eliminarRegistro(EnvioStock $registro){

        if($registro->estado == 'recibido' || $registro->usuarioEnvio != Auth::user()->name){

            session()->flash('mensaje', 'Este articulo ya esta recibido, NO se puede eliminar. O debe ser el mismo Usuario');

        }else{


                    // Borra el registro
            $deleted = Stock::where('id', $registro->eliminarIdStock)->delete();
            $registro->delete();
            session()->flash('mensaje', 'Envio ELiminado');

        }

    }

    public function render()
    {
        return view('livewire.stock.historico-envio',[
            'registros'=>EnvioStock::select([
                'envio_stocks.*',
                DB::raw('(SELECT nombre FROM depositos WHERE id = envio_stocks.depositoOrigen_id) AS depositoOrigen'),
                DB::raw('(SELECT nombre FROM depositos WHERE id = envio_stocks.depositoDestino_id) AS depositoDestino'),
            ])
            ->where('empresa_id', 1)
            ->whereDate('created_at','>=', $this->fechaFiltro)
            ->orderBy('envio_stocks.created_at','DESC')
            ->paginate(10),
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
