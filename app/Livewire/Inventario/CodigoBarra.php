<?php

namespace App\Livewire\Inventario;

use Livewire\Component;


use Livewire\Attributes\Validate;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\Session;


use App\Models\Inventario;



class CodigoBarra extends Component
{

    use WithPagination;


    #[Session(key: 'arrayInventario')] 
    public $arrayInventario=[];

    public $datoBuscado;


    public function borrar(){
        $this->arrayInventario = [];
    }
    
    public function borrarArticulo($index){

        // dd($index);
        unset($this->arrayInventario[$index]);

    }
    public function cargarArticulo(Inventario $articulo,$precio){

        // dd($articulo);
        // "id" => 99015
        // "codigo" => "ANG001"
        // "detalle" => "Angulo 1/2 x 1/8"
        // "costo" => 7669.56
        // "precio1" => 13641.85
        // "precio2" => 15006.03
        // "precio3" => 15688.12
        // "porcentaje" => 47.0
        // "iva" => 21.0
        // "rubro" => "ANGULOS"
        // "proveedor" => "JUAN NAVARRO"
        // "marca" => null
        // "pesable" => "no"
        // "controlStock" => "no"
        // "imagen" => ""
        // "empresa_id" => 12
        // "created_at" => "2024-08-29 16:42:48"
        // "updated_at" => "2024-08-29 16:42:48"

        switch ($precio) {
            case 'precio1':
                # code...
                $precio = $articulo->precio1;
                break;
            case 'precio2':
                # code...
                $precio = $articulo->precio2;
                break;
            case 'precio3':
                # code...
                $precio = $articulo->precio3;
                break;
            
            default:
                # code...
                break;
        }

        if(is_numeric( $articulo->codigo) AND strlen($articulo->codigo) == 13){

            $tipoDeCodigoBarras='EAN13';

        }else{

            $tipoDeCodigoBarras='Code128';

        }

        $this->arrayInventario[]= array(
            'id'=>$articulo->id,
            'codigo'=>$articulo->codigo,
            'detalle'=>$articulo->detalle,
            'precio'=>$precio,
            'tipo'=>$tipoDeCodigoBarras,

        );


    }


    public function render()
    {
        return view('livewire.inventario.codigo-barra',[
            'inventario'=> Inventario::where('empresa_id',Auth::user()->empresa_id)
                            ->whereAny([
                                'codigo',
                                'detalle',
                                'rubro',
                                'proveedor',
                                'marca',
                            ], 'like', '%'.$this->datoBuscado.'%')
                            ->paginate(10),
            
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
