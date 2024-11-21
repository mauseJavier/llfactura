<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 



use App\Models\productoComprobante;
use App\Models\Comprobante;


class CargarComprobanteController extends Controller
{
    //

    // #[Session(key: 'carrito')] 
    public $carrito;
    public $cliente;

    public  function cargar (Request $request,Comprobante $comp) {

        // "id" => 57760
        // "comprobante_id" => 29899
        // "comprobante_numero" => 29899
        // "codigo" => "Yrtutyy"
        // "detalle" => "De tjgthy"
        // "porcentaje" => 0.0
        // "precioLista" => 2200.0
        // "descuento" => 0.0
        // "precio" => 2200.0
        // "iva" => 21.0
        // "cantidad" => 1.0
        // "costo" => 1000.0
        // "rubro" => "General"
        // "proveedor" => "General"
        // "marca" => "General"
        // "controlStock" => "si"
        // "fecha" => "2024-11-20"
        // "tipoComp" => "remito"
        // "idFormaPago" => 1
        // "idFormaPago2" => 1
        // "ptoVta" => 0
        // "usuario" => "JAVIER LLFACTURA"
        // "empresa_id" => 1

        // dd(productoComprobante::where('comprobante_id',$comp->id)->get());


            //BORRAMOS LAS SESSIONES POR LAS DUDAS 
            $this->carrito = null;
            $this->cliente = null;
    
            // dd($comp);
            if(isset($comp->DocTipo)){
    
    
                $productos = productoComprobante::where('comprobante_id',$comp->id)
                                            ->where('empresa_id',Auth()->user()->empresa_id)->get();
                // dump($comp);

                if( count($productos) == 0){
                    dd('SIN DATOS');
                }
    
                $this->cliente = array(
                    'DocTipo'=> $comp->DocTipo,
                    'cuitCliente'=> $comp->cuitCliente,
                    'razonSocial'=> $comp->razonSocial,
                    'tipoContribuyente'=> $comp->tipoContribuyente,
                    'domicilio'=> $comp->domicilio,
                    'leyenda'=> $comp->leyenda,
                    'idFormaPago'=> $comp->idFormaPago,
                );
    
                foreach ($productos as $key => $value) {
                    # code...
                    $this->carrito['carrito'][] = array(
                        'codigo'=>$value->codigo,
                        'detalle'=>$value->detalle,
    
                        'porcentaje'=> $value->porcentaje,
                        'precioLista'=> $value->precioLista ,
                        'descuento'=> $value->descuento ,
                        'precio'=> round( $value->precio,2),
    
                        'costo'=> $value->costo,
    
                        'iva'=>$value->iva,
                        'cantidad'=>$value->cantidad,
                        'rubro'=>$value->rubro,
                        'proveedor'=>$value->proveedor,
                        'marca'=>$value->marca,
    
                        'controlStock'=>$value->controlStock,
                        'subtotal'=> round( $value->precio * $value->cantidad,2) ,
            
                        ) ;
                }
    
    
                    $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
                    $cantidadArticulos = 0 ;
    
                    // dd($this->carrito['carrito'] );
    
                    foreach ($this->carrito['carrito'] as $item) {
    
                        // dd($this->carrito['carrito']);
                        $totalSubtotal += $item['subtotal'];
                        $cantidadArticulos += $item['cantidad'];
    
                    }
    
                    $this->carrito['total']=  round($totalSubtotal,2);
                    $this->carrito['articulos']=  $cantidadArticulos;
    
                    // $this->redirectRoute('nuevoComprobante');
                    // Store a piece of data in the session...
                    session(['carrito' => $this->carrito]);
                    session(['cliente' => $this->cliente]);

                    // dd($this->carrito);
                    return redirect()->route('nuevoComprobante');
    
    
            }else{
                session()->flash('mensaje', 'No existe Presupuesto.');
    
            }
    
    
    
        
    }


}
