<?php

namespace App\Livewire\Mesas;

use Livewire\Component;

use Livewire\WithPagination;
use Livewire\Attributes\Session;



use Illuminate\Support\Facades\Auth;

use App\Models\Mesa;
use App\Models\Inventario;


class ModificarMesa extends Component
{

    use WithPagination;



    public $mesa;

    public $seleccionPrecio;
    public $porcentaje=0;

    #[Session(key: 'mesaCarrito')] 
    public $mesaCarrito;




    public $razonSocial,$comentario,$datoBuscado,$cantidad=1,
            $data;



    public function guardarPedidoMesa(){

        // dump($this->data);
        // dump($this->mesaCarrito);

        if(isset($this->data['mesaCarrito'])){

            // Combinar los elementos de mesaCarrito
            $mesaCarritoCombinado = array_merge($this->data['mesaCarrito'], $this->mesaCarrito['mesaCarrito']);
    
            // Sumar el total de los artículos
            $totalArticulos = $this->data['articulos'] + $this->mesaCarrito['articulos'];
    
            // Sumar el total de los subtotales
            $totalSubtotales = array_sum(array_column($mesaCarritoCombinado, 'subtotal'));
    
            // Crear el array resultante
            $resultado = [
                "total" => $totalSubtotales,
                "articulos" => $totalArticulos,
                "mesaCarrito" => $mesaCarritoCombinado
            ];
    
    
            // dd($resultado);
            $this->mesa->data = $resultado;

    
        }else{
            $this->mesa->data = $this->mesaCarrito;

        }


        $this->mesa->save();
        $this->mesaCarrito = null;

        $this->data = $this->mesa->data;

        session()->flash('mensaje', 'Guardado');



    }

    public function borrarArticulo($index)
    {
            $array = $this->mesaCarrito['mesaCarrito'];

            session()->flash('mensaje', 'Artículo eliminado: ' . $this->mesaCarrito['mesaCarrito'][$index]['detalle']);

            unset($array[$index]);//esto borra el elemento 
            $array = array_values($array);//esto acomoda los index            

            $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
            $cantidadArticulos = 0 ;

            foreach ($array as $item) {

                // dd($this->mesaCarrito['mesaCarrito']);
                $totalSubtotal += $item['subtotal'];
                $cantidadArticulos += $item['cantidad'];
            }

            $this->mesaCarrito['mesaCarrito']= $array;//asignamos los nuevos valores 
            $this->mesaCarrito['total']= round($totalSubtotal,2);
            $this->mesaCarrito['articulos']=  $cantidadArticulos;

            if($cantidadArticulos == 0){
                $this->borrarCarrito();

                // $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
                // $this->tamañoGrillaVenta(0);

            }else{
                // $this->dispatch('actualizarCarrito', total: $this->mesaCarrito['total'] , articulos: $this->mesaCarrito['articulos']);

                // $this->tamañoGrillaVenta(count($this->mesaCarrito['mesaCarrito']));
            
            }

            //dump($this->mesaCarrito);


    }

    public function borrarCarrito(){
        $this->mesaCarrito=null;
        $this->cliente=null;


        // $this->dispatch('actualizarCarrito', total: 0 , articulos: 0);
        // $this->tamañoGrillaVenta(0);

        $this->porcentaje=0;


    }


    public function crearmesaCarrito($articulo)
    {

        $validated = $this->validate([
            'cantidad' => 'required|numeric|min:0.01',

        ], [
            'cantidad.required' => 'El campo cantidad a enviar es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad a enviar debe ser un número.',
            'cantidad.min' => 'El campo cantidad a enviar debe ser mayor que 0.01.',

            'porcentaje.required' => 'El campo porcentaje a enviar es obligatorio.',
            'porcentaje.numeric' => 'El campo porcentaje a enviar debe ser un número.',
        ]);



            $nuevoArticulo = array(
                'codigo'=>$articulo[0]->codigo,
                'detalle'=>$articulo[0]->detalle,

                'porcentaje'=> $this->porcentaje,
                'precioLista'=> $this->porcentaje < 0 ? $articulo[0]->precio :  round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio),2),
                'descuento'=> $this->porcentaje < 0 ? round($articulo[0]->precio * $this->porcentaje / 100 ,2) : 0 ,

                'precio'=> round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio),2),
                'costo'=>round($articulo[0]->costo,2),
                'iva'=>$articulo[0]->iva,
                'cantidad'=>$this->cantidad,
                'rubro'=>$articulo[0]->rubro,
                'proveedor'=>$articulo[0]->proveedor,
                'marca'=>$articulo[0]->marca,

                'controlStock'=>$articulo[0]->controlStock,
                'subtotal'=> round(($articulo[0]->precio * $this->porcentaje / 100 + $articulo[0]->precio) * $this->cantidad,2) ,

                ) ;

                if(!isset($this->mesaCarrito['mesaCarrito'])){
                    $this->mesaCarrito['mesaCarrito']=[];
                }

                array_unshift($this->mesaCarrito['mesaCarrito'], $nuevoArticulo);

                $totalSubtotal = 0; // Inicializamos la variable para acumular los subtotales
                $cantidadArticulos = 0 ;

                foreach ($this->mesaCarrito['mesaCarrito'] as $item) {

                    // dd($this->mesaCarrito['mesaCarrito']);
                    $totalSubtotal += $item['subtotal'];
                    $cantidadArticulos += $item['cantidad'];

                }

                $this->mesaCarrito['total']=  round($totalSubtotal,2);
                $this->mesaCarrito['articulos']=  $cantidadArticulos;

                // $this->mesaCarrito['mesaCarrito'] = array_reverse($this->mesaCarrito['mesaCarrito']);  // Invierte el orden
                // usort($this->mesaCarrito['mesaCarrito'], function($a, $b) {
                //     return $b <=> $a;  // Orden descendente
                // });
                // // Invertir el array
                // $this->mesaCarrito['mesaCarrito'] = array_reverse($this->mesaCarrito['mesaCarrito'],true);
                
                
                
                $this->porcentaje=0;
                
                
                // $this->tamañoGrillaVenta(count($this->mesaCarrito['mesaCarrito']));
                
                // $this->dispatch('actualizarmesaCarrito', total: $this->mesaCarrito['total'] , articulos: $this->mesaCarrito['articulos']);
                
                // dd($this->mesaCarrito['mesaCarrito']);
                

    }

    public function cargar($id){

        $articulo = Inventario::select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
        ->where('id', $id)
        ->get();

        // dd($articulo);
        // "codigo" => "9373374570051"
        // "detalle" => "Almohadas"
        // "precio" => 96.26
        // "iva" => 10.5
        // "rubro" => "General"
        // "proveedor" => "Vital"
        // "controlStock" => "no"
        // "costo" => 160.25
        // "marca" => "Ford"
        
        $this->crearmesaCarrito($articulo);
        $this->cantidad = 1;
        
        // if(!$this->bloquearDetalle){
        //     $this->datoBuscado= '';
            
        // }
        
    }

    public function mount(Mesa $mesa){


        $this->seleccionPrecio ='precio1';

        $this->mesa = $mesa;
        $this->razonSocial = $mesa->razonSocial;
        $this->datos= $mesa->datos;

        $this->data = json_decode($mesa->data,true);

        // dd(json_decode($mesa->data,true));

        
    }


    public function guardarrazonSocial(){

        $this->mesa->razonSocial = $this->razonSocial;

        $this->mesa->save();



    }


    public function restarCantidad(){

        $this->cantidad --;

        if($this->cantidad <= 0){

            $this->cantidad =1;


        }

    }

    public function sumarCantidad(){

        $this->cantidad ++;

    }
    
    public function render()
    {
    

        $this->resetPage();

        
        return view('livewire.mesas.modificar-mesa',[
            
            
            'inventario'=> Inventario::
            select('id','codigo','detalle',$this->seleccionPrecio .' as precio')
            ->where('empresa_id', Auth::user()->empresa_id)
            ->whereAny([
                'codigo',
                'detalle',
                'rubro',
                'proveedor'
            ], 'LIKE', "%{$this->datoBuscado}%")     
            ->orderBy('created_at','DESC')                           
            ->paginate(5),

        ])
        ->extends('layouts.app')
        ->section('main');;
    }
}
