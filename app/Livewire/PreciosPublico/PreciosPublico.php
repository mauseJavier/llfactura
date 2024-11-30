<?php

namespace App\Livewire\PreciosPublico;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Models\Empresa;

 

class PreciosPublico extends Component
{

    public $searchQuery = ''; // Variable para realizar la búsqueda

    public $keys = []; // Arreglo para almacenar las teclas presionadas

    public $seleccionPrecio = 'precio1';

    public $detalle,$precio;

    public function captureKey($key)
    {

        if($key == 'Enter'){
            // Actualizar el término de búsqueda uniendo las teclas
            $this->searchQuery = implode('', $this->keys);

            $this->search();

            $this->keys = [];

        }else{

            $this->keys[] = $key;
        }
    }

    public function search()
    {

        if (strlen($this->searchQuery) == 13 AND $this->searchQuery[0] == '2') {

                
    

                    // Asignamos el valor de las variables con peso codbar
                    // TOMA EL PLU
                    $plu =ltrim(substr($this->datoBuscado, 2, 5), '0') ; // 5 CIFRAS CODIGO
                    // TOMA EL PESO
                    // Extraer los últimos 4 dígitos $numero = "2000110012250";
                    $parteDecimal = substr($this->searchQuery, 9,3);
                    $parteEntera = substr($this->searchQuery, 7,2);

                    // Convertir a formato decimal 1.225
                    $this->cantidad = floatval($parteEntera .'.'.$parteDecimal);


                    $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
                    ->where('codigo', $plu)
                    ->where('empresa_id', Auth::user()->empresa_id)
                    ->get();

                    // dd($parteEntera .'.'.$parteDecimal);
            
            
        }else{

            $articulo = DB::table('inventarios')->select('codigo','detalle',$this->seleccionPrecio.' as precio','iva','rubro','proveedor','controlStock','costo','marca')
                            ->where('codigo', $this->searchQuery)
                            ->where('empresa_id', Auth::user()->empresa_id)
                            ->get();
            // dd('no pesable');
        }



        // dd($articulo);
        if(count($articulo) > 0){

            // dd($articulo[0]);

            $this->detalle = $articulo[0]->detalle;
            $this->precio = '$'. number_format( $articulo[0]->precio,2,',');


            // $this->crearCarrito($articulo);
            // $this->datoBuscado= '';
            // $this->cantidad = 1;
        }else{

            $this->detalle = '';
            $this->precio = 'Sin Resultado';

        }
        
    }
    


    public function render()
    {
        return view('livewire.precios-publico.precios-publico',[

            'empresa' => Empresa::find(Auth::user()->empresa_id) ,

        ])
            ->extends('layouts.soloPicoCSS')
            ->section('body')
            ;
    }
}
