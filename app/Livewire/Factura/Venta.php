<?php

namespace App\Livewire\Factura;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Inventario;



class Venta extends Component
{
    use WithPagination;


    public $datoBuscado = '';
    public $cantidad = 1;
    public $seleccionPrecio = 'precio1';
    public $esconderCelular = 'esconderCelular';
    public $mostrarCelular = '';
    public $estadoModal = '';
    public $porcentaje=0;

    public $modificarDetalle;
    public $modificarPrecio;
    public $modificarCantidad;
    public $modificarKey;

    #[Session(key: 'carrito')] 
    public $carrito;

    public function mount() 
    { 
    }

    public function buscarCargar()
    {
        $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock')
                        ->where('codigo', $this->datoBuscado)
                        ->where('empresa_id', Auth::user()->empresa_id)
                        ->get();
        

        // dd(count($articulo));
        if(count($articulo) > 0){

            $this->crearCarrito($articulo);
            $this->datoBuscado= '';
            $this->cantidad = 1;
        }
        
    }
    
    public function cargar($id){
        
        $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock')
        ->where('id', $id)
        ->get();
        
        $this->crearCarrito($articulo);
        
        $this->cantidad = 1;
        
    }
    
    public function crearCarrito($articulo)
    {

        $validated = $this->validate([
            'cantidad' => 'required|numeric|min:1',
        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.',
        ]);

            $this->carrito['carrito'][] = array(
                'codigo'=>$articulo[0]->codigo,
                'detalle'=>$articulo[0]->detalle,
                'precio'=> round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio),2),
                'iva'=>$articulo[0]->iva,
                'cantidad'=>$this->cantidad,
                'rubro'=>$articulo[0]->rubro,
                'proveedor'=>$articulo[0]->proveedor,
                'controlStock'=>$articulo[0]->controlStock,
                'subtotal'=> round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio) * $this->cantidad,2) ,

                ) ;

                $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
                $cantidadArticulos = 0 ;

                foreach ($this->carrito['carrito'] as $item) {

                    // dd($this->carrito['carrito']);
                    $totalSubtotal += $item['subtotal'];
                    $cantidadArticulos += $item['cantidad'];

                }

                $this->carrito['total']=  round($totalSubtotal,2);
                $this->carrito['articulos']=  $cantidadArticulos;

    }

    public function aplicarPorcentaje(){

        $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
        $cantidadArticulos = 0 ;
        foreach ($this->carrito['carrito'] as $key => $item) {

            $precio = $this->carrito['carrito'][$key]['precio'];
            $cantidad = $this->carrito['carrito'][$key]['cantidad'];

            $precio =round($precio+($precio * $this->porcentaje /100));

            $this->carrito['carrito'][$key]['precio'] = $precio ;
            $this->carrito['carrito'][$key]['subtotal'] = round($precio * $cantidad);

            // dd($this->carrito['carrito']);
            $totalSubtotal += round($precio * $cantidad);
            $cantidadArticulos += $item['cantidad'];
        }

        $this->carrito['total']= round($totalSubtotal,2);
        $this->carrito['articulos']=  $cantidadArticulos;

        session()->flash('mensaje', '% aplicado: '. $this->porcentaje);

    }

    public function borrarArticulo($index)
    {
            $array = $this->carrito['carrito'];

            unset($array[$index]);//esto borra el elemento 
            $array = array_values($array);//esto acomoda los index            

            $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
            $cantidadArticulos = 0 ;

            foreach ($array as $item) {

                // dd($this->carrito['carrito']);
                $totalSubtotal += $item['subtotal'];
                $cantidadArticulos += $item['cantidad'];
            }

            $this->carrito['carrito']=$array;//asignamos los nuevos valores 
            $this->carrito['total']= round($totalSubtotal,2);
            $this->carrito['articulos']=  $cantidadArticulos;

            if($totalSubtotal == 0){
                $this->borrarCarrito();
                $this->esconderCelular = 'esconderCelular';
                $this->mostrarCelular= '';
            }

    }

    public function abrirModal($key)
    {

        $this->modificarDetalle = $this->carrito['carrito'][$key]['detalle'];
        $this->modificarPrecio = $this->carrito['carrito'][$key]['precio'];
        $this->modificarCantidad = $this->carrito['carrito'][$key]['cantidad'];
        $this->modificarKey = $key;


        $this->estadoModal= 'open';

    }

    public function modificarCarrito(){

        $this->carrito['carrito'][$this->modificarKey]['detalle'] = $this->modificarDetalle ;
        $this->carrito['carrito'][$this->modificarKey]['precio'] =    $this->modificarPrecio ;
        $this->carrito['carrito'][$this->modificarKey]['cantidad'] =  $this->modificarCantidad ;    
        $this->carrito['carrito'][$this->modificarKey]['subtotal'] =  round( $this->modificarPrecio * $this->modificarCantidad,2) ;   

        $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
        $cantidadArticulos = 0 ;
        foreach ($this->carrito['carrito'] as $item) {

            // dd($this->carrito['carrito']);
            $totalSubtotal += $item['subtotal'];
            $cantidadArticulos += $item['cantidad'];
        }

        $this->carrito['total']= round($totalSubtotal,2);
        $this->carrito['articulos']=  $cantidadArticulos;

        $this->cerrarModal();
    }

    public function cerrarModal(){
        $this->estadoModal='';
    }


    public function borrarCarrito(){
        $this->carrito=null;
        $this->cliente=null;

        $this->esconderCelular = 'esconderCelular';
        $this->mostrarCelular= '';
    }

    public function render()
    {
        return view('livewire.factura.venta',
            [     
            'inventario'=> DB::table('inventarios')
                                    ->select('id','codigo','detalle',$this->seleccionPrecio .' as precio')
                                    ->where('empresa_id', Auth::user()->empresa_id)
                                    ->whereAny([
                                        'codigo',
                                        'detalle',
                                        'rubro',
                                        'proveedor'
                                    ], 'LIKE', "%{$this->datoBuscado}%")                                
                                    ->paginate(30),
            ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

    public function sumarCantidad()
    {
        $validated = $this->validate([
            'cantidad' => 'required|numeric|min:1',
        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.',
        ]);

        $this->cantidad +=1;
    }

    public function restarCantidad()
    {
        $validated = $this->validate([
            'cantidad' => 'required|numeric|min:1',
        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.',
        ]);

        if($this->cantidad == 1){
            
        }else
        {
            $this->cantidad -=1;
        }
        
    }

    public function cambiar(){
       
        if($this->esconderCelular == 'esconderCelular'){
            $this->esconderCelular = '';
            $this->mostrarCelular= 'esconderCelular';

        }else{
            $this->esconderCelular = 'esconderCelular';
            $this->mostrarCelular= '';

        }
    }


}
