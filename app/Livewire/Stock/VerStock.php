<?php

namespace App\Livewire\Stock;

use Livewire\Component;

use App\Events\DescontarStockEvent;

use Livewire\WithPagination;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Inventario;
use App\Models\Stock;
use App\Models\Empresa;
use App\Models\Deposito;
use App\Models\EnvioStock;

class VerStock extends Component
{
    use WithPagination;

    public $empresa;
    public $depositos;
    // public $stock;
    public $movimientosArticulo=[];

    //variables para el deposito nuevo 
    public $nombreDeposito;
    public $comentarioDeposito;


    public $datoBuscado;

    //variables para los envios
    public $cantidadEnviar=1;
    public $depositoDestino_id;


    public function enviarArticulo($depositoOrigen_id,$codigo,$detalle){

        // dump($this->cantidadEnviar);
        // dump($this->depositoDestino_id);
        // dump($depositoOrigen_id);
        // dump($codigo);
        // dump($detalle);

        $validated = $this->validate([
            'cantidadEnviar' => 'required|numeric|min:1',
        ], [
            'cantidadEnviar.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidadEnviar.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidadEnviar.min' => 'El campo cantidad a enviar debe ser mayor que 0.',
        ]);

        if($this->depositoDestino_id == $depositoOrigen_id ){

            session()->flash('mensaje', 'No puede enviar articulos al mismo deposito.');
            $this->cantidadEnviar = 1;

        }else{


            //AK DESCUENTA EL STOCK
            $descuento = Stock::create([
                'codigo'=> $codigo,
                'detalle'=> $detalle,
                'deposito_id'=>$depositoOrigen_id,
                'stock'=>($this->cantidadEnviar * -1),
                'comentario'=>'Envio stock',
                'usuario'=>Auth::user()->name,
                'empresa_id'=>$this->empresa->id,
    
            ]);

            $nuevoEnvio = EnvioStock::create([
                'codigo'=> $codigo,
                'detalle'=> $detalle,
                'depositoOrigen_id'=>$depositoOrigen_id,
                'depositoDestino_id'=>$this->depositoDestino_id,
                'stock'=>$this->cantidadEnviar,
                'estado'=>'enviado', //$table->enum('estado', ['enviado', 'recibido']);
                'comentario'=>'Envio Stock',
                'usuarioEnvio'=>Auth::user()->name,
                'empresa_id'=> $this->empresa->id,
                'eliminarIdStock'=> $descuento->id,//PARA CUANDO ELIMINAS EL ENVIO
            ]);
    

    
            session()->flash('mensaje', 'Envio Correcto. Articulo: '. $detalle . ' Codigo: '. $codigo);
            $this->cantidadEnviar = 1;
            
        }


    }

    public function mount()
    {
        $this->modalDetalleArticulo = 'close';

        $this->datoBuscado = '';

        $this->empresa = Empresa::find(Auth::user()->empresa_id);

        $this->depositos = Deposito::where('empresa_id',$this->empresa->id)
                                     ->where('id','!=', Auth::user()->deposito_id ) //para que no se pueda enviar al mismo deposito
                                    ->get();
                    
        if($this->depositos->isEmpty()){

            $this->depositos = Deposito::where('empresa_id',$this->empresa->id)->get();

            if($this->depositos->isEmpty()){

                $this->depositos = Deposito::create([
                    'nombre'=> 'General',
                    'comentario'=> 'General',
                    'empresa_id'=> Auth::user()->empresa_id,
                ]);

                $this->depositos = NULL;

            }else{// SIGNIFICA QUE NO ESTA BASIO PERO TIENE UN SOLO DEPOSITO Y NO SE PUEDEN HACER ENVIOS 

                $this->depositos = NULL;
            }


        }else{//AK TIEEN MAS DE UN DEPOSITO Y SE PUEDEN HACER ENVIOS 

            $this->depositoDestino_id = $this->depositos[0]['id'];
        }



    }




    public function guardarDeposito(){

        $validated = $this->validate([
            'nombreDeposito' => 'required',
        ], [
            'cantidadEnviar.required' => 'El campo Nombre es obligatorio.',
        ]);


        Deposito::updateOrCreate([
            'nombre'=> $this->nombreDeposito,
            'empresa_id'=> $this->empresa->id,
        ],
        [
            'comentario'=> $this->comentarioDeposito,
            
        ]);


        $this->nombreDeposito = '';
        $this->comentarioDeposito='';

        session()->flash('mensaje', 'Guardado.');
    }

    public function render()
    {
        return view('livewire.stock.ver-stock',[     
                'stock'=> DB::table('stocks as a')
                    ->select('a.codigo', 'a.detalle', DB::raw('SUM(a.stock) as sumStock'), 'b.nombre as nombreDeposito', 'a.deposito_id as depositoId', 'a.empresa_id')
                    ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
                    ->where('a.empresa_id', $this->empresa->id)
                    ->where(function($query) {
                        $query->where('a.codigo', 'like', '%' . $this->datoBuscado . '%')
                            ->orWhere('a.detalle', 'like', '%' . $this->datoBuscado . '%')
                            ->orWhere('b.nombre', 'like', '%' . $this->datoBuscado . '%');
                    })
                    ->groupBy('a.codigo', 'a.detalle', 'b.nombre', 'a.deposito_id', 'a.empresa_id')
                    ->orderBy('sumStock')
                    ->paginate(10),
            ])        
        ->extends('layouts.app')
        ->section('main'); 
 
    }
}


// DB::select('select a.codigo,a.detalle, sum(a.stock) as sumStock, b.nombre as nombreDeposito,
// a.deposito_id as depositoId , a.empresa_id
//                 FROM stocks a, depositos b 
//                     WHERE a.deposito_id = b.id AND a.empresa_id = '.$this->empresa->id.' AND 
                    
//                        (
//                             a.codigo like "%'.$this->datoBuscado.'%" OR 
//                             a.detalle like "%'.$this->datoBuscado.'%" OR
//                             b.nombre like "%'.$this->datoBuscado.'%"
//                        )
                    
//                     GROUP BY a.codigo, a.detalle, b.nombre,
//                         a.deposito_id,a.empresa_id
//                     ORDER BY a.stock '
//                 )->paginate(3)
