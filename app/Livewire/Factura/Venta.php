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
    public $estadoModal = '';
    public $porcentaje=0;

    public $tamañoGrillaVenta=0;

    public $modificarDetalle;
    public $modificarPrecio;
    public $modificarCantidad;
    public $modificarKey;

    public $checkPrecio1;
    public $checkPrecio2;
    public $checkPrecio3;


    #[Session(key: 'carrito')] 
    public $carrito;

  

    public function mount() 
    { 

        if(isset($this->carrito['total'])){

            $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);
            $this->tamañoGrillaVenta(count($this->carrito['carrito']));
        }else{
        }

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
        $this->datoBuscado= '';
        $this->cantidad = 1;
        
    }
    
    public function crearCarrito($articulo)
    {

        $validated = $this->validate([
            'cantidad' => 'required|numeric',
            'porcentaje' => 'required|numeric',

        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.',

            'porcentaje.required' => 'El campo porcentaje a enviar es obligatorio.',
            'porcentaje.numeric' => 'El campo porcentaje a enviar debe ser un número.',
        ]);



            $nuevoArticulo = array(
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

                if(!isset($this->carrito['carrito'])){
                    $this->carrito['carrito']=[];
                }

                array_unshift($this->carrito['carrito'], $nuevoArticulo);

                $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
                $cantidadArticulos = 0 ;

                foreach ($this->carrito['carrito'] as $item) {

                    // dd($this->carrito['carrito']);
                    $totalSubtotal += $item['subtotal'];
                    $cantidadArticulos += $item['cantidad'];

                }

                $this->carrito['total']=  round($totalSubtotal,2);
                $this->carrito['articulos']=  $cantidadArticulos;

                // $this->carrito['carrito'] = array_reverse($this->carrito['carrito']);  // Invierte el orden
                // usort($this->carrito['carrito'], function($a, $b) {
                //     return $b <=> $a;  // Orden descendente
                // });
                // // Invertir el array
                // $this->carrito['carrito'] = array_reverse($this->carrito['carrito'],true);
                
                
                
                $this->porcentaje=0;
                
                
                $this->tamañoGrillaVenta(count($this->carrito['carrito']));
                
                $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);
                
                // dd($this->carrito['carrito']);
                

    }

    public function aplicarPorcentaje(){

        $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
        $cantidadArticulos = 0 ;
        foreach ($this->carrito['carrito'] as $key => $item) {

            $precio = $this->carrito['carrito'][$key]['precio'];
            $cantidad = $this->carrito['carrito'][$key]['cantidad'];

            $precio =round($precio+($precio * $this->porcentaje /100),2);

            $this->carrito['carrito'][$key]['precio'] = $precio ;
            $this->carrito['carrito'][$key]['subtotal'] = round($precio * $cantidad,2);

            // dd($this->carrito['carrito']);
            $totalSubtotal += round($precio * $cantidad,2);
            $cantidadArticulos += $item['cantidad'];
        }

        $this->carrito['total']= round($totalSubtotal,2);
        $this->carrito['articulos']=  $cantidadArticulos;

        $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);


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

            $this->carrito['carrito']= $array;//asignamos los nuevos valores 
            $this->carrito['total']= round($totalSubtotal,2);
            $this->carrito['articulos']=  $cantidadArticulos;

            if($totalSubtotal == 0){
                $this->borrarCarrito();

                $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
                $this->tamañoGrillaVenta(0);

            }else{
                $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);

                $this->tamañoGrillaVenta(count($this->carrito['carrito']));
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
        $this->carrito['carrito'][$this->modificarKey]['precio'] =    round(floatval($this->modificarPrecio),2) ; 
        $this->carrito['carrito'][$this->modificarKey]['cantidad'] =  round(floatval($this->modificarCantidad),2) ;    
        $this->carrito['carrito'][$this->modificarKey]['subtotal'] =  round( floatval($this->modificarPrecio) * floatval($this->modificarCantidad),2) ;   

        $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
        $cantidadArticulos = 0 ;
        foreach ($this->carrito['carrito'] as $item) {

            // dd($this->carrito['carrito']);
            $totalSubtotal += $item['subtotal'];
            $cantidadArticulos += $item['cantidad'];
        }

        $this->carrito['total']= round($totalSubtotal,2);
        $this->carrito['articulos']=  $cantidadArticulos;


        // dd($this->checkPrecio1 .' '.$this->checkPrecio2 .' '.$this->checkPrecio3);
        if($this->checkPrecio1){
            Inventario::where('codigo', $this->carrito['carrito'][$this->modificarKey]['codigo'])
                        ->where('empresa_id', Auth::user()->empresa_id)
                ->update(['precio1' => round(floatval($this->modificarPrecio),2)]);
        }

        if($this->checkPrecio2){
            Inventario::where('codigo', $this->carrito['carrito'][$this->modificarKey]['codigo'])
                        ->where('empresa_id', Auth::user()->empresa_id)
                ->update(['precio2' => round(floatval($this->modificarPrecio),2)]);
        }

        if($this->checkPrecio3){
            Inventario::where('codigo', $this->carrito['carrito'][$this->modificarKey]['codigo'])
                        ->where('empresa_id', Auth::user()->empresa_id)
                ->update(['precio3' => round(floatval($this->modificarPrecio),2)]);
        }


        $this->checkPrecio1 = false;
        $this->checkPrecio2 = false;
        $this->checkPrecio3 = false;

        $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);


        $this->cerrarModal();
    }

    public function cerrarModal(){
        $this->estadoModal='';
    }


    public function borrarCarrito(){
        $this->carrito=null;
        $this->cliente=null;


        $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
        $this->tamañoGrillaVenta(0);


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

    public function tamañoGrillaVenta($cantidadArticulos){

        $maximoFilas = 4;
        if($cantidadArticulos <= $maximoFilas){

            $this->tamañoGrillaVenta =  20 +($cantidadArticulos * 70);
        }else{
            $this->tamañoGrillaVenta = 20 + $maximoFilas * 70;
        }

    }




}
