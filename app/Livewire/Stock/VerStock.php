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
    public $usuario;

    public $depositos;
    public $todosDepositos;
    public $nombreDepositoUsuario;
    public $idDepositoUsuario;
    public $movimientosArticulo=[];

    //variables para el deposito nuevo 
    public $nombreDeposito;
    public $comentarioDeposito;


    public $datoBuscado;

    //variables para los envios
    public $cantidadEnviar=1;
    public $depositoDestino_id;

    // DATOS PARA LA MODIFICACION DE STOCK
    public $codigo;
    public $detalle;
    public $nuevoStock=1;
    public $idDepositoGuardar;
    public $comentario;


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

        $this->usuario = Auth::user();
        $this->empresa = Empresa::find(Auth::user()->empresa_id);
        
        $this->todosDepositos = Deposito::where('empresa_id',$this->empresa->id)
        ->get();

        $depositoUsuario = (array_filter($this->todosDepositos->toArray(), function($k) {
            return $k['id'] == Auth::user()->deposito_id;
        }));

        foreach ($depositoUsuario as $key => $value) {
            $this->nombreDepositoUsuario = $value['nombre'];
            $this->idDepositoUsuario = $value['id'];
        }

        $this->idDepositoGuardar =$this->idDepositoUsuario ;


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
        

        // $this->stock();
        

        
    }
    

    public function traerStock(){ // ESTE NO FUNCIONA EN PRODUCCION 


        $this->stock = DB::table('stocks as a')
        ->select('a.codigo', 'a.detalle', DB::raw('SUM(a.stock) as sumStock'), 'b.nombre as nombreDeposito', 'a.deposito_id as depositoId', 'a.empresa_id')
        ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
        
        ->where('a.empresa_id', $this->empresa->id)
        ->where('b.empresa_id', $this->empresa->id)
        
        ->where(function($query) {
            $query->where('a.codigo', 'like', '%' . $this->datoBuscado . '%')
                  ->orWhere('a.detalle', 'like', '%' . $this->datoBuscado . '%');
        })
        ->groupBy('a.codigo', 'a.detalle', 'b.nombre', 'a.deposito_id', 'a.empresa_id')
        ->orderBy('sumStock')
        ->get();



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

    public function asignarCodigoDetalle($codigo,$detalle,$despositoId){

        // dump($codigo .' '.$detalle);
        $this->codigo=$codigo;
        $this->detalle=$detalle;
        $this->idDepositoGuardar = $despositoId;

    }
    public function modificarStockArticulo(){

        // dump($this->codigo);
        // dump($this->detalle);
        // dump($this->idDepositoGuardar);
        // dump($this->nuevoStock);

        // dd();
        $validated = $this->validate([ 
            'codigo' => 'required|min:1',
            'detalle' => 'required|min:1',
            'nuevoStock'=>'numeric',
            'comentario'=> 'max:200'
        ]);


        $n = Stock::create([
            'codigo'=>$this->codigo,
            'detalle'=>$this->detalle,
            'deposito_id'=>$this->idDepositoGuardar,
            'stock'=>$this->nuevoStock,
            'comentario'=> $this->nuevoStock > 0 ? 'Ingreso '. $this->comentario : 'Descuento '. $this->comentario ,
            'usuario'=>$this->usuario->name,
            'empresa_id'=> $this->empresa->id,
        ]);

        $this->nuevoStock = 1;
        $this->comentario = '';


        session()->flash('modificarStock', 'Stock Guardado. id-'. $n->id);



    }

    public function eliminarSockDeposito($codigo,$depositoId){

        // dd($codigo,$depositoId);
        $deleted = Stock::where('codigo', $codigo)->where('deposito_id',$depositoId)->delete();

        // dd($deleted);
        session()->flash('mensaje', 'Stock Eliminado. (Filas eliminadas: '.$deleted.')');

    }

    public function render()
    {
        return view('livewire.stock.ver-stock',[     

                'stock'=>  DB::table('stocks as a')
                ->select('a.id','a.codigo', 'a.detalle', DB::raw('SUM(a.stock) as sumStock'), 'b.nombre as nombreDeposito', 'a.deposito_id as depositoId', 'a.empresa_id')
                ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
                
                ->where('a.empresa_id', $this->empresa->id)
                ->where('b.empresa_id', $this->empresa->id)
                
                ->where(function($query) {
                    $query->where('a.codigo', 'like', '%' . $this->datoBuscado . '%')
                          ->orWhere('a.detalle', 'like', '%' . $this->datoBuscado . '%');
                })
                ->when($this->idDepositoUsuario, function ($query, $idDeposito) {
                    return $query->where('a.deposito_id', $idDeposito);
                })


                ->groupBy('a.codigo', 'a.detalle', 'b.nombre', 'a.deposito_id', 'a.empresa_id')
                ->orderBy('sumStock')
                ->paginate(10) ,


            ])        
        ->extends('layouts.app')
        ->section('main'); 
 
    }
}

//  ESTA ES LA ULTIMA CONSULTA
//         DB::table('stocks as a')
//         ->select('a.codigo', 'c.detalle', DB::raw('SUM(a.stock) as sumStock'), 'b.nombre as nombreDeposito', 'a.deposito_id as depositoId', 'a.empresa_id')
//         ->join('depositos as b', function($join) {
//         $join->on('a.deposito_id', '=', 'b.id')
//         ->where('b.empresa_id', '=', $this->empresa->id);
//         })
//         ->join('inventarios as c', function($join) {
//         $join->on('a.codigo', '=', 'c.codigo')
//         ->where('c.empresa_id', '=', $this->empresa->id);
//         })
//         ->where('a.empresa_id', $this->empresa->id)
//         ->where(function($query) {
//         $query->where('a.codigo', 'like', '%' . $this->datoBuscado . '%')
//         ->orWhere('c.detalle', 'like', '%' . $this->datoBuscado . '%')
//         ->orWhere('b.nombre', 'like', '%' . $this->datoBuscado . '%');
//         })
//         ->groupBy('a.codigo', 'c.detalle', 'b.nombre', 'a.deposito_id', 'a.empresa_id')
//         ->orderBy('sumStock')
//         ->paginate(10)



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


// DB::table('stocks as a')
// ->select('a.codigo', 'c.detalle', DB::raw('SUM(a.stock) as sumStock'), 'b.nombre as nombreDeposito', 'a.deposito_id as depositoId', 'a.empresa_id')
// ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
// ->join('inventarios as c', 'a.codigo', '=', 'c.codigo')

// ->where('a.empresa_id', $this->empresa->id)
// ->where(function($query) {
// $query->where('a.codigo', 'like', '%' . $this->datoBuscado . '%')
// ->orWhere('c.detalle', 'like', '%' . $this->datoBuscado . '%')
// ->orWhere('b.nombre', 'like', '%' . $this->datoBuscado . '%');
// })
// ->groupBy('a.codigo', 'c.detalle', 'b.nombre', 'a.deposito_id', 'a.empresa_id')
// ->orderBy('sumStock')
// ->paginate(10)


// DB::table('stocks as a')
// ->select('a.codigo', 'c.detalle', DB::raw('SUM(a.stock) as sumStock'), 'b.nombre as nombreDeposito', 'a.deposito_id as depositoId', 'a.empresa_id')
// ->join('depositos as b', 'a.deposito_id', '=', 'b.id')
// ->join('inventarios as c', 'a.codigo', '=', 'c.codigo')
// ->where('a.empresa_id', $this->empresa->id)
// ->where(function($query) {
// $query->where('a.codigo', 'like', '%' . $this->datoBuscado . '%')
// ->orWhere('c.detalle', 'like', '%' . $this->datoBuscado . '%')
// ->orWhere('b.nombre', 'like', '%' . $this->datoBuscado . '%');
// })
// ->groupBy('a.codigo', 'c.detalle', 'b.nombre', 'a.deposito_id', 'a.empresa_id')
// ->orderBy('sumStock')
// ->paginate(10);
