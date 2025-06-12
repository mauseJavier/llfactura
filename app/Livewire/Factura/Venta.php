<?php

namespace App\Livewire\Factura;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Session;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Inventario;



class Venta extends Component
{
    use WithPagination;


    public $datoBuscado = '';
    public $cantidad = 1;
    public $seleccionPrecio;
    public $estadoModal = '';
    public $porcentaje=0;

    public $tamañoGrillaVenta=0;

    public $modificarDetalle;
    public $modificarPrecio;
    public $modificarCantidad;
    public $modificarPorcentaje=0;
    public $modificarKey;

    public $checkPrecio1;
    public $checkPrecio2;
    public $checkPrecio3;


    public $bloquearDetalle;


    #[Session(key: 'carrito')] 
    public $carrito;

  

    public function mount() 
    { 


        if(isset($this->carrito['total'])){

            $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);
            $this->tamañoGrillaVenta(count($this->carrito['carrito']));
        }else{
        }

        //POR QUE JAVIER CONTRERAS UTILIZA EL PRECIO 2 CON EL 10 POR CIENTO 
        $this->seleccionPrecio = (Auth::user()->empresa_id == 26) ? 'precio2' : 'precio1';

        $this->bloquearDetalle=null;

    }


    function dePruebaCodigoPesable(){

        if (strlen($txtProducto) == 13) {
            // Pregunta si el primer carácter es "2"
            if ($txtProducto[0] == '2') {
                $peso = true;
    
                // ################## P E S A B L E ##################################
                if ($peso) {
                    // Asignamos el valor de las variables con peso codbar
                    // TOMA EL PLU
                    $plu = substr($txtProducto, 2, 5); // 5 CIFRAS CODIGO
                    // TOMA EL PESO
                    $pesoCodBar = substr($txtProducto, 7, 1) . '.' . substr($txtProducto, 9, 1) . substr($txtProducto, 10, 1);
                    $peso = false; // Volvemos a dejar en falso el peso
    
                    // Asignar valores a las variables
                    $request->merge(['txt_cantidad' => $pesoCodBar, 'Txt_Producto' => $plu]);
                    // Simular el envío del formulario
                    return redirect()->route('ruta.donde.se.envia', ['txt_cantidad' => $pesoCodBar, 'Txt_Producto' => $plu]);
                }
            } else {
                // Asignamos el valor de las variables
                $codigo = $rs->codigo; // Suponiendo que $rs es el resultado de una consulta
                $detalle = $rs->detalle; // detalle
                $cantidad = (float)$txtCantidad; // cantidad
                $costo = $rs->costo; // costo
                $iva = $rs->iva; // IVA
            }
        }
    


    }


    // public function ViejobuscarCargar($data = null)
    // {

    //     if ($data === null) {
    //         $data = $this->datoBuscado;
    //     }

    //     if (strlen($data) == 13 AND $data[0] == '2') {

                
    

    //                 // Asignamos el valor de las variables con peso codbar
    //                 // TOMA EL PLU
    //                 $plu =ltrim(substr($data, 2, 5), '0') ; // 5 CIFRAS CODIGO

    //                 // dd($plu);
    //                 // TOMA EL PESO
    //                 // Extraer los últimos 4 dígitos $numero = "2000110012250";
    //                 $parteDecimal = substr($data, 9,3);
    //                 $parteEntera = substr($data, 7,2);

    //                 // Convertir a formato decimal 1.225
    //                 $this->cantidad = floatval($parteEntera .'.'.$parteDecimal);


    //                 $articulo[0] = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
    //                 ->where('codigo', $plu)
    //                 ->where('pesable', 'si')
    //                 ->where('empresa_id', Auth::user()->empresa_id)
    //                 ->get();

    //                 // dd($parteEntera .'.'.$parteDecimal);

    //                 // dd($articulo[0]);
                    
    //                 if(count($articulo[0]) == 0){

    //                     $articulo[0] = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
    //                     ->where('codigo', $data)
    //                     ->where('empresa_id', Auth::user()->empresa_id)
    //                     ->get();
    //                     // dd('no pesable');
    //                     $this->cantidad = 1;

    //                 }
            
            
    //     }else{

    //         $articulo[0] = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
    //                         ->where('codigo', $data)
    //                         ->where('empresa_id', Auth::user()->empresa_id)
    //                         ->get();
    //         // dd('no pesable');

    //     }



    //     // dd($articulo[0]);
    //     if(count($articulo[0]) > 0){

    //         // Mostrar la info del artículo en el log de Laravel
    //         // Log::info('Artículo Encontrado:', (array) $articulo[0][0]);



    //         $this->crearCarrito($articulo[0]);
    //         $this->datoBuscado= '';
    //         $this->cantidad = 1;

    //     }
        
    // }

    public function buscarCargar($data)
    {
        // Log::info('buscarCargar iniciado con valor: ' . $data);

        if (empty(trim($data))) {
            // Log::warning('buscarCargar: valor vacío, no se hace nada.');
            return; // O alguna otra lógica si el valor es vacío
        }

        try {
            // Tu lógica para buscar el artículo
        if (strlen($data) == 13 AND $data[0] == '2') {

                    
        

                        // Asignamos el valor de las variables con peso codbar
                        // TOMA EL PLU
                        $plu =ltrim(substr($data, 2, 5), '0') ; // 5 CIFRAS CODIGO

                        // dd($plu);
                        // TOMA EL PESO
                        // Extraer los últimos 4 dígitos $numero = "2000110012250";
                        $parteDecimal = substr($data, 9,3);
                        $parteEntera = substr($data, 7,2);

                        // Convertir a formato decimal 1.225
                        $this->cantidad = floatval($parteEntera .'.'.$parteDecimal);


                        $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
                        ->where('codigo', $plu)
                        ->where('pesable', 'si')
                        ->where('empresa_id', Auth::user()->empresa_id)
                        ->first();

                        // dd($parteEntera .'.'.$parteDecimal);

                        // dd($articulo[0]);
                        
                        // if(count($articulo[0]) == 0){

                        if(!$articulo){

                            $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
                            ->where('codigo', $data)
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->first();
                            // dd('no pesable');
                            $this->cantidad = 1;

                        }
                
                
            }else{

                $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
                                ->where('codigo', $data)
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->first();
                // dd('no pesable');

            }



            // dd($articulo[0]);
            if($articulo){

                // Mostrar la info del artículo en el log de Laravel
                // Log::info('Artículo Encontrado:', (array) $articulo);

                $this->crearCarrito($articulo);

                $this->datoBuscado= '';

                
            }
            
            $this->cantidad = 1;

            // if ($articulo) {
            //     // Tu lógica para cargar al carrito
            //     Log::info('Artículo encontrado y cargado: ' . $articulo->codigo);
            // } else {
            //     Log::warning('Artículo no encontrado para valor: ' . $data);
            //     // Quizás emitir un mensaje al frontend
            //     // $this->dispatch('mostrarError', 'Artículo no encontrado');
            // }

        } catch (\Exception $e) {
            Log::error('Error en buscarCargar: ' . $e->getMessage());
            // Quizás emitir un mensaje de error al frontend
            // $this->dispatch('mostrarError', 'Error procesando la búsqueda.');
        }
        // Log::info('buscarCargar finalizado.');
    }
    
    public function cargar($id){
        
        $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
        ->where('id', $id)
        ->first();
        
        $this->crearCarrito($articulo);
        $this->cantidad = 1;
        
        if(!$this->bloquearDetalle){
            $this->datoBuscado= '';
            
        }
        
    }
    
    public function crearCarrito($articulo)
    {

        $validated = $this->validate([
            'cantidad' => 'required|numeric|min:0.01',
            'porcentaje' => 'required|numeric',

        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.01.',

            'porcentaje.required' => 'El campo porcentaje a enviar es obligatorio.',
            'porcentaje.numeric' => 'El campo porcentaje a enviar debe ser un número.',
        ]);

        // dd($articulo->codigo);


            $nuevoArticulo = array(
                'codigo'=>$articulo->codigo,
                'detalle'=>$articulo->detalle,

                'porcentaje'=> $this->porcentaje,
                'precioLista'=> $this->porcentaje < 0 ? $articulo->precio :  round(($articulo->precio * $this->porcentaje / 100 + $articulo->precio),2),
                'descuento'=> $this->porcentaje < 0 ? round($articulo->precio * $this->porcentaje / 100 ,2) : 0 ,

                'precio'=> round(($articulo->precio * $this->porcentaje / 100 + $articulo->precio),2),
                'costo'=>round($articulo->costo,2),
                'iva'=>$articulo->iva,
                'cantidad'=>$this->cantidad,
                'rubro'=>$articulo->rubro,
                'proveedor'=>$articulo->proveedor,
                'marca'=>$articulo->marca,

                'controlStock'=>$articulo->controlStock,
                'subtotal'=> round(($articulo->precio * $this->porcentaje / 100 + $articulo->precio) * $this->cantidad,2) ,

                ) ;


                // Log::info('Artículo Agregado:', (array) $nuevoArticulo);

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

                $this->dispatch('grilla-actualizada');


                            

    }

    // public function aplicarPorcentaje(){ //VIEJO

        //     $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
        //     $cantidadArticulos = 0 ;
        //     foreach ($this->carrito['carrito'] as $key => $item) {

        //         $precio = $this->carrito['carrito'][$key]['precio'];
        //         $cantidad = $this->carrito['carrito'][$key]['cantidad'];

        //         $this->carrito['carrito'][$key]['porcentaje']= $this->porcentaje;
        //         $this->carrito['carrito'][$key]['precioLista']= $this->porcentaje < 0 ? $precio : round($precio+($precio * $this->porcentaje /100),2);
        //         $this->carrito['carrito'][$key]['descuento']= $this->porcentaje < 0 ? round(($precio * $this->porcentaje /100),2) : 0;



        //         $precio =round($precio+($precio * $this->porcentaje /100),2);




        //         $this->carrito['carrito'][$key]['precio'] = $precio ;
        //         $this->carrito['carrito'][$key]['subtotal'] = round($precio * $cantidad,2);

        //         // dd($this->carrito['carrito']);
        //         $totalSubtotal += round($precio * $cantidad,2);
        //         $cantidadArticulos += $item['cantidad'];
        //     }

        //     $this->carrito['total']= round($totalSubtotal,2);
        //     $this->carrito['articulos']=  $cantidadArticulos;

        //     $this->porcentaje=0;


        //     dd($this->carrito);

        //     $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);


        //     session()->flash('mensaje', '% aplicado: '. $this->porcentaje);

    // }

    public function aplicarPorcentaje()
    {
        $totalSubtotal = 0;
        $cantidadArticulos = 0;
    
        foreach ($this->carrito['carrito'] as $key => $item) {
            $cantidad = $item['cantidad'];
    
            // Si no tiene precioLista, se lo asignamos desde el precio actual
            if (!isset($item['precioLista'])) {
                $item['precioLista'] = $item['precio'];
            }
    
            $precioBase = $item['precioLista'];
            $this->carrito['carrito'][$key]['porcentaje'] = $this->porcentaje;
    
            if ($this->porcentaje == 0) {
                // Restaurar el precio original
                $precioFinal = $precioBase;
                $descuento = 0;
    
            } elseif ($this->porcentaje < 0) {
                // Descuento
                $precioFinal = round($precioBase + ($precioBase * $this->porcentaje / 100), 2);
                $descuento = round(($precioBase * $this->porcentaje / 100), 2);
    
            } else {
                // Incremento: también actualiza el precioLista
                $precioFinal = round($precioBase + ($precioBase * $this->porcentaje / 100), 2);
                $descuento = 0;
                $this->carrito['carrito'][$key]['precioLista'] = $precioFinal; // Nuevo precio base
            }
    
            $this->carrito['carrito'][$key]['precio'] = $precioFinal;
            $this->carrito['carrito'][$key]['descuento'] = $descuento;
            $this->carrito['carrito'][$key]['subtotal'] = round($precioFinal * $cantidad, 2);
    
            $totalSubtotal += $this->carrito['carrito'][$key]['subtotal'];
            $cantidadArticulos += $cantidad;
        }
    
        $this->carrito['total'] = round($totalSubtotal, 2);
        $this->carrito['articulos'] = $cantidadArticulos;
    
        
        // dump($this->carrito);
        
        
        $this->dispatch('actualizarCarrito', total: $this->carrito['total'], articulos: $this->carrito['articulos']);
        session()->flash('mensaje', 'Porcentaje aplicado y actualizado: ' . $this->porcentaje);
        
        $this->porcentaje = 0;
    }
    


    public function quitarPorcentaje(){

        // $precio =round($this->modificarPrecio+($this->modificarPrecio * $this->modificarPorcentaje /100),2);
        // $this->carrito['carrito'][$this->modificarKey]['detalle'] = $this->modificarDetalle ;


        $precio =  $this->carrito['carrito'][$this->modificarKey]['precioLista'] ; 
        $this->carrito['carrito'][$this->modificarKey]['precio'] = $precio;
        $this->modificarPrecio = $precio;
        // $this->carrito['carrito'][$this->modificarKey]['precioLista'] = $this->modificarPorcentaje < 0 ? round(floatval($this->modificarPrecio),2) : round($this->modificarPrecio+($this->modificarPrecio * $this->modificarPorcentaje /100),2);


        // $this->carrito['carrito'][$this->modificarKey]['cantidad'] =  round(floatval($this->modificarCantidad),2) ;    
        $this->carrito['carrito'][$this->modificarKey]['subtotal'] =  round( floatval($precio) * floatval($this->modificarCantidad),2) ;   

        $this->carrito['carrito'][$this->modificarKey]['descuento']= 0;
        $this->carrito['carrito'][$this->modificarKey]['porcentaje']= 0;

    }

    public function borrarArticulo($index)
    {
            $array = $this->carrito['carrito'];

            session()->flash('mensaje', 'Artículo eliminado: ' . $this->carrito['carrito'][$index]['detalle']);

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

            if($cantidadArticulos == 0){
                $this->borrarCarrito();

                $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
                $this->tamañoGrillaVenta(0);

            }else{
                $this->dispatch('actualizarCarrito', total: $this->carrito['total'] , articulos: $this->carrito['articulos']);

                $this->tamañoGrillaVenta(count($this->carrito['carrito']));
            
            }

            //dump($this->carrito);


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


        $validated = $this->validate([
            'modificarCantidad' => 'required|numeric|min:0.01',

        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.01.',

            'porcentaje.required' => 'El campo porcentaje a enviar es obligatorio.',
            'porcentaje.numeric' => 'El campo porcentaje a enviar debe ser un número.',
        ]);

        $precio =round($this->modificarPrecio+($this->modificarPrecio * $this->modificarPorcentaje /100),2);


        $this->carrito['carrito'][$this->modificarKey]['detalle'] = $this->modificarDetalle ;
        $this->carrito['carrito'][$this->modificarKey]['precio'] =    $precio ; 
        $this->carrito['carrito'][$this->modificarKey]['precioLista'] = $this->modificarPorcentaje < 0 ? round(floatval($this->modificarPrecio),2) : round($this->modificarPrecio+($this->modificarPrecio * $this->modificarPorcentaje /100),2);


        $this->carrito['carrito'][$this->modificarKey]['cantidad'] =  round(floatval($this->modificarCantidad),2) ;    
        $this->carrito['carrito'][$this->modificarKey]['subtotal'] =  round( floatval($precio) * floatval($this->modificarCantidad),2) ;   

        $this->carrito['carrito'][$this->modificarKey]['descuento']= $this->modificarPorcentaje < 0 ? round(($this->modificarPrecio * $this->modificarPorcentaje /100),2) : 0;
        $this->carrito['carrito'][$this->modificarKey]['porcentaje']= $this->modificarPorcentaje;

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


        $this->modificarPorcentaje=0;
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

        $this->porcentaje=0;


    }


    // En tu componente Livewire
    public function rendering($view, $data) // O al final del método render(), o después de actualizar $inventario
    {
        $this->dispatch('grilla-actualizada');
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
                                        'detalle'
                                    ], 'LIKE', "%{$this->datoBuscado}%")     
                                    ->orderBy('created_at','DESC')                           
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

            $this->tamañoGrillaVenta =  25 +($cantidadArticulos * 50);
        }else{
            $this->tamañoGrillaVenta = 25 + $maximoFilas * 50;
        }

    }


}
