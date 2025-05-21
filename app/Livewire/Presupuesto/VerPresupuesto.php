<?php

namespace App\Livewire\Presupuesto;

use Livewire\Component;
use Livewire\WithPagination;


use Livewire\Attributes\Session;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Models\Presupuesto;
use App\Models\ProductoPresupuesto;


class VerPresupuesto extends Component
{

        use WithPagination;


    public $presupuesto;
    public $total;

    public $datoBuscado;

    #[Session(key: 'carrito')] 
    public $carrito;

    #[Session(key: 'cliente')] 
    public $cliente;

    public function traerProductos(Presupuesto $presupuesto){
        // dd($presupuesto);

        $this->presupuesto = $presupuesto;
        $this->total =$this->presupuesto->total;

    }

    


    public function cargarPresupuesto(){

        //BORRAMOS LAS SESSIONES POR LAS DUDAS 
        $this->carrito = null;
        $this->cliente = null;

        // dd($this->presupuesto);
        if(isset($this->presupuesto->DocTipo)){


            $productos = ProductoPresupuesto::where('presupuesto_id',$this->presupuesto->id)->get();
            // dump($this->presupuesto);

            $this->cliente = array(
                'DocTipo'=> $this->presupuesto->DocTipo,
                'cuitCliente'=> $this->presupuesto->cuitCliente,
                'razonSocial'=> $this->presupuesto->razonSocial,
                'tipoContribuyente'=> $this->presupuesto->tipoContribuyente,
                'domicilio'=> $this->presupuesto->domicilio,
                'leyenda'=> $this->presupuesto->leyenda,
                'idFormaPago'=> $this->presupuesto->idFormaPago,
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

                $this->redirectRoute('nuevoComprobante');


        }else{
            session()->flash('mensaje', 'No existe Presupuesto.');

        }



    }


    public function imprimirPresupuesto(){
        $this->redirectRoute('formatoPDF',['comprobante_id'=>$this->presupuesto->id,
        'tipo'=>'presupuesto']);
    }

    public function mount(){

        $this->presupuesto = Presupuesto::where('empresa_id',Auth::user()->empresa_id)->first();
        if($this->presupuesto == null){

            $this->presupuesto = (object) array('id' => 0, 'total'=>0);
            $this->total = $this->presupuesto->total ? $this->presupuesto->total : 0;    
        }else{

            $this->total =$this->presupuesto->total;

        }

    }

    public function borrarPresupuesto($numero){
            
        DB::transaction(function () use ($numero) {

            $presupuestoEliminados = DB::table('presupuestos')->where('id',$numero)->where('empresa_id',Auth::user()->empresa_id)->delete();
    
            $productosEliminados = DB::table('producto_presupuestos')->where('presupuesto_id',$numero)->where('empresa_id',Auth::user()->empresa_id)->delete();
        });


        // dd('presupuesto '.$presupuestoEliminados .' productos'. $productosEliminados);
        return redirect()->route('presupuesto');
    }

    public function render()
    {
        return view('livewire.presupuesto.ver-presupuesto',[
            'presupuestos'=> Presupuesto::where('empresa_id',Auth::user()->empresa_id)
                                        ->whereAny([
                                            'numero',
                                            'razonSocial',
                                            'usuario'
                                        ], 'LIKE', '%'.$this->datoBuscado.'%')->orderby('created_at','DESC')->paginate(10),
            'productos'=> ProductoPresupuesto::where('presupuesto_id',$this->presupuesto->id)->get(),
            'numeroPresupuesto'=>$this->presupuesto->id,


        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
