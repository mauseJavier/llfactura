<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

use Livewire\Attributes\Session;

use Illuminate\Support\Carbon;


use Livewire\Attributes\Validate;
use Livewire\WithPagination;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EdicionMultiple extends Component
{
    use WithPagination;

    #[Session(key: 'articulosEdicionMultiple')] 
    public $articulosEdicionMultiple;

    public $datoBuscado ='' ;

    public $imprimirReporte=false;


    // #[Validate('required|min:0|numeric')] 
    public $porcentajePrecio1=0;
    // #[Validate('required|min:0|numeric')] 
    public $porcentajePrecio2=0;
    // #[Validate('required|min:0|numeric')] 
    public $porcentajePrecio3=0;


    public $porcentaje1=0;
    public $porcentaje2=0;
    public $porcentaje3=0;

    public $arrayFiltros=['codigo','detalle','rubro','proveedor','marca'];


    public function modificarFiltros($valor){

            $pos = array_search($valor, $this->arrayFiltros);

            if($pos !== false){

                unset($this->arrayFiltros[$pos]);
            }else{

                $this->arrayFiltros[]= $valor;

            }

    }


    public function modificarPrecio(){
        // dd('hola');

        $affected = DB::table('inventarios')
                    ->where('empresa_id', Auth::user()->empresa_id)
                    ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")
                    ->update(['precio1' => DB::raw('round(precio1 + ( precio1 * '.$this->porcentaje1.'/100),2)'),
                                'precio2' => DB::raw('round(precio2 + ( precio2 * '.$this->porcentaje2.'/100),2)'),
                                'precio3' => DB::raw('round(precio3 + ( precio3 * '.$this->porcentaje3.'/100),2)'),
                                'updated_at'=> Carbon::now()]);
               
        // dump($affected);

        if($this->imprimirReporte){

            $this->articulosEdicionMultiple = [
                'filtros'=>$this->arrayFiltros,
                'datoBuscado'=>$this->datoBuscado,
               ];
               
        
            $this->redirectRoute('reporteEdicionMultiple');

        }else{

            session()->flash('mensaje', 'Filas actualizadas. '. $affected);

        }




    }

    public function actualizar(){

        // $this->validate();

        $validated = $this->validate([ 
            'porcentajePrecio1' => 'required|min:0|numeric',
            'porcentajePrecio2' => 'required|min:0|numeric',
            'porcentajePrecio3' => 'required|min:0|numeric',
        ]);

        $this->porcentaje1 = $this->porcentajePrecio1;
        $this->porcentaje2 = $this->porcentajePrecio2;
        $this->porcentaje3 = $this->porcentajePrecio3;

        $this->render();
    }
    


    public function render()
    {


        return view('livewire.inventario.edicion-multiple',[
            'inventario'=> DB::table('inventarios')
                                ->select('*',DB::raw('round(precio1 + ( precio1 * '.$this->porcentaje1.'/100),2) as NuevoPrecio1'),
                                                DB::raw('round(precio2 + ( precio2 * '.$this->porcentaje2.'/100),2) as NuevoPrecio2'),
                                                DB::raw('round(precio3 + ( precio3 * '.$this->porcentaje3.'/100),2) as NuevoPrecio3'),)
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")        
                                ->paginate(30),
        ])
        ->extends('layouts.app')
        ->section('main');

        // DB::table('inventarios')
        // ->select('*', 'precio1 *' .$this->porcentajePrecio1.' as NuevoPrecio1')
        // ->where('empresa_id', Auth::user()->empresa_id)
        // ->whereAny([
        //     'codigo',
        //     'detalle',
        //     'rubro',
        //     'proveedor',
        //     'marca'
        // ], 'LIKE', "%$this->datoBuscado%")                                
        // ->paginate(30)
    }
}
