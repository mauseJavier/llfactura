<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

use Livewire\Attributes\Validate;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Inventario;
use App\Models\Empresa;
use App\Models\ListaPrecio;
use App\Models\Rubro;
use App\Models\Proveedor;
use App\Models\Deposito;
use App\Models\Stock;
use App\Models\Marca;

class VerInventario extends Component
{

    use WithPagination;

    public $usuario;
    public $empresa;
    public $depositos;
    public $datoBuscado ='' ;
    public $modal = 'close';
    public $masDatos = false;
    public $modalStock = 'close';


    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('max:250', message: 'Maximo 250 caracter')]
    public $codigo;
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:1', message: 'Minimo 1 caracter')]
    #[Validate('max:250', message: 'Maximo 250 caracter')]
    public $detalle;
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Minimo 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $costo=0; //'required|numeric|min:0',
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Minimo 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $porcentaje1 =0;
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Minimo 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $precio1=0;
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Minimo 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $precio2=0;
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Minimo 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $precio3=0;    
    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Mayor a 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $iva;
    
    #[Validate('required', message: 'Requerido')]
    public $rubro='General';
    #[Validate('required', message: 'Requerido')]
    public $proveedor='General';
    #[Validate('required', message: 'Requerido')]
    public $marca='General';
    public $pesable = 'no';
    public $controlStock = 'no';
    public $imagen;

    public $ivaIncluido = false;

    public $idDeposito;

    // GUARDAR RUBRO
    public $nuevoRubro = '';
    public $nuevoProveedor = '';
    public $nuevaMarca = '';

    public $nuevaLista='';
    public $porcentajeLista=0;

    //MODIFICAR EL STOCK

    #[Validate('required', message: 'Requerido')]
    #[Validate('min:0', message: 'Mayor a 0')]
    #[Validate('numeric', message: 'Solo Numeros')]
    public $nuevoStock=1;


    public function cambiarModalStock($codigo = '',$detalle=''){        
        if($this->modalStock == 'open'){
            $this->modalStock = 'close';

            $this->nuevoStock = 1;
            $this->codigo = '';
            $this->detalle = '';

        }else{

            $this->codigo = $codigo;
            $this->detalle = $detalle;


            $this->modalStock = 'open';


        }
    }
    public function modificarStockArticulo(){

        // dump($this->codigo);
        // dump($this->detalle);
        // dump($this->idDeposito);
        // dump($this->nuevoStock);

        $this->validate();


        $n = Stock::create([
            'codigo'=>$this->codigo,
            'detalle'=>$this->detalle,
            'deposito_id'=>$this->idDeposito,
            'stock'=>$this->nuevoStock,
            'comentario'=>'Ingreso',
            'usuario'=>$this->usuario->name,
            'empresa_id'=> $this->empresa->id,
        ]);

        $this->nuevoStock = 1;

        session()->flash('modificarStock', 'Stock Guardado. id-'. $n->id);



    }
    
    public function guardarMarca(){

        $validated = $this->validate([ 
            'nuevaMarca' => 'required|min:3',
        ]);

        $nuevaMarca = Marca::create([
            'nombre'=> $this->nuevaMarca,
            'comentario'=> $this->nuevaMarca,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($nuevaMarca){

            session()->flash('marcaGuardar', 'Marca '.$nuevaMarca->nombre.' Guardado.');
        }else{

            session()->flash('marcaGuardar', 'Ocurrio un Error');
        }


        $this->nuevaMarca = '';
    }

    public function guardarLista(){

        $validated = $this->validate([ 
            'nuevaLista' => 'required|min:3',
            'porcentajeLista' => 'required|numeric',
        ]);

        $listaGuardada = ListaPrecio::create([
            'nombre'=> $this->nuevaLista,
            'porcentaje'=> $this->porcentajeLista,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($listaGuardada){

            session()->flash('mensajeLista', 'Lista '.$listaGuardada->nombre.' Guardado.');
        }else{

            session()->flash('mensajeLista', 'Ocurrio un Error');
        }


        $this->nuevaLista = '';
        $this->porcentajeLista = 0;

    }

    public function guardarProveedor(){

        $validated = $this->validate([ 
            'nuevoProveedor' => 'required|min:3',
        ]);

        $nuevoProveedor = Proveedor::create([
            'nombre'=> $this->nuevoProveedor,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($nuevoProveedor){

            session()->flash('proveedorGuardado', 'Proveedor '.$nuevoProveedor->nombre.' Guardado.');
        }else{
            session()->flash('proveedorGuardado', 'Ocurrio un error');
        }
        $this->nuevoProveedor = '';
    }

    public function guardarRubro(){

        $validated = $this->validate([ 
            'nuevoRubro' => 'required|min:3',
        ]);

        $nuevoRubro = Rubro::create([
            'nombre'=> $this->nuevoRubro,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($nuevoRubro){

            session()->flash('rubroGuardado', 'Rubro '.$this->nuevoRubro.' Guardado.');
        }else{
            session()->flash('rubroGuardado', 'Ocurrio un error');

        }

        $this->nuevoRubro = '';
    }

    public function mount()
    {
        $this->usuario = Auth::user();
        $this->empresa = Empresa::find(Auth::user()->empresa_id);
        $this->iva = $this->empresa->ivaDefecto;       
        $this->depositos = Deposito::where('empresa_id',$this->empresa->id)->get();

        $this->idDeposito = ($this->depositos[0]->id);

        
    }

    public function calcularPrecios(){

        // dd($this->ivaIncluido);

        $this->validate();

        if($this->ivaIncluido){

            $costo_mas_iva = round($this->costo / (1+ (doubleval($this->iva)/100)),2);
        }else{
            $costo_mas_iva = round($this->costo +($this->costo * doubleval($this->iva) / 100),2);
        }

        // dump($costo_mas_iva);


        $this->precio1 = round( $costo_mas_iva + ($costo_mas_iva * $this->porcentaje1 /100) ,2);
        $this->precio2 = round( $costo_mas_iva + ($costo_mas_iva * $this->empresa->precio2 /100) ,2);
        $this->precio3 = round( $costo_mas_iva + ($costo_mas_iva * $this->empresa->precio3 /100) ,2);


    }

    public function editarId(inventario $articulo){
        // dd($articulo);
        // array:15 [▼
        //     "id" => 2
        //     "codigo" => "50733354"
        //     "detalle" => "maiores"
        //     "costo" => 979.36
        //     "precio1" => 99.67
        //     "precio2" => 71.53
        //     "precio3" => 138.17
        //     "iva" => 21.0
        //     "rubro" => "General"
        //     "proveedor" => "MAUSE"
        //     "pesable" => "si"
        //     "imagen" => null
        //     "empresa_id" => 1
        //     "created_at" => "2024-05-20 17:19:06"
        //     "updated_at" => "2024-05-20 17:19:06"
        // ]

        $this->codigo= $articulo->codigo;
        $this->detalle=$articulo->detalle;
        $this->costo=$articulo->costo;
        $this->precio1=$articulo->precio1;
        $this->precio2=$articulo->precio2;
        $this->precio3=$articulo->precio3;
        $this->iva = $articulo->iva;
        $this->rubro=$articulo->rubro;
        $this->proveedor=$articulo->proveedor;
        $this->pesable = $articulo->pesable;
        $this->controlStock = $articulo->controlStock;
        $this->imagen=$articulo->imagen;


        $this->modal="open";
    }

    public function guardarArticulo(){

        $this->validate();

        $nuevoArticulo = inventario::updateOrCreate(
            ['codigo' => $this->codigo, 'empresa_id' => $this->empresa->id],
            [
                'detalle'=> $this->detalle,
                'costo'=> round($this->costo,2),
                'precio1'=> round($this->precio1,2),
                'precio2'=> round($this->precio2,2),
                'precio3'=> round($this->precio3,2),
                'iva'=> round($this->iva,2),
                'rubro'=> $this->rubro,
                'proveedor'=> $this->proveedor,
                'marca'=> $this->marca,
                'pesable'=> $this->pesable,
                'controlStock'=> $this->controlStock,
                'imagen'=> $this->imagen,
            ]
        );

        // $nuevoArticulo = inventario::create([
        //     'codigo'=> $this->codigo,
        //     'empresa_id'=> $this->empresa->id,

        //     'detalle'=> $this->detalle,
        //     'costo'=> round($this->costo,2),
        //     'precio1'=> round($this->precio1,2),
        //     'precio2'=> round($this->precio2,2),
        //     'precio3'=> round($this->precio3,2),
        //     'iva'=> round($this->iva,2),
        //     'rubro'=> $this->rubro,
        //     'proveedor'=> $this->proveedor,
        //     'pesable'=> $this->pesable,
        //     'imagen'=> $this->imagen,
        // ]);

        $this->codigo='';
        $this->detalle='';
        $this->costo=0;
        $this->porcentaje1 =0;
        $this->precio1=0;
        $this->precio2=0;
        $this->precio3=0;
        $this->iva = $this->empresa->ivaDefecto;
        $this->rubro='General';
        $this->proveedor='General';
        $this->marca='General';
        $this->pesable = 'no';
        $this->controlStock = 'no';
        $this->imagen='';
        $this->ivaIncluido=false;

        $this->datoBuscado= $nuevoArticulo->codigo;
        $this->modal='close';
    }


    public function render()
    {
        // return view('livewire.inventario.ver-inventario');
        return view('livewire.inventario.ver-inventario',
        [     
            'inventario'=> DB::table('inventarios')
                                // ->select('id','codigo','detalle','precio1 as precio')
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->whereAny([
                                    'codigo',
                                    'detalle',
                                    'rubro',
                                    'proveedor',
                                    'marca'
                                ], 'LIKE', "%$this->datoBuscado%")                                
                                ->paginate(30),

            'listaPrecios' => ListaPrecio::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
            'listaRubros' => Rubro::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
            'listaProveedores' => Proveedor::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
            'listaMarcas' => Marca::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
        ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

    public function cambiarModal(){

        if($this->modal == 'close'){
            $this->modal = 'open';
        }else{
            $this->modal = 'close';

            $this->codigo='';
            $this->detalle='';
            $this->costo=0;
            $this->porcentaje1 =0;
            $this->precio1=0;
            $this->precio2=0;
            $this->precio3=0;
            $this->iva = $this->empresa->ivaDefecto;
            $this->rubro='General';
            $this->proveedor='General';
            $this->pesable = 'no';
            $this->imagen='';
            $this->ivaIncluido= false;
            
        }
    }

}
