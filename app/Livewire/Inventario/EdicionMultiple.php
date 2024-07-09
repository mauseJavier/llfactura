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



    public $imprimirReporte=false;
    public $precioFijo=false;



    // #[Validate('required|min:0|numeric')] 
    public $porcentajePrecio1=0;
    // #[Validate('required|min:0|numeric')] 
    public $porcentajePrecio2=0;
    // #[Validate('required|min:0|numeric')] 
    public $porcentajePrecio3=0;
    
    public $porcentaje1=0;
    public $porcentaje2=0;
    public $porcentaje3=0;

    public $precioFijo1=0;
    public $precioFijo2=0;
    public $precioFijo3=0;

    public $fijo1=0;
    public $fijo2=0;
    public $fijo3=0;




    public $criterioFiltro='rubro';
    public $opciones;
    public $datoFiltro;

    public function actualizarCriterio(){
        // dd($this->criterioFiltro);
        $this->opciones = DB::table('inventarios')
        ->select($this->criterioFiltro.' as nombre')
        ->where('empresa_id', Auth::user()->empresa_id)
        ->groupBy($this->criterioFiltro)->get();
    }


    public function mount(){
        $this->actualizarCriterio();
    }


    public function modificarPrecio(){
        // dd('hola');

        if($this->precioFijo){
            
            // $affected = DB::table('inventarios')
            // ->where('empresa_id', Auth::user()->empresa_id)
            // ->where($this->criterioFiltro,$this->datoFiltro)
            // // ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")
            // ->update(['precio1' => DB::raw('round( '.$this->precioFijo1.',2)'),
            //             'precio2' => DB::raw('round( '.$this->precioFijo2.',2)'),
            //             'precio3' => DB::raw('round( '.$this->precioFijo3.',2)'),
            //             'updated_at'=> Carbon::now()]);

            // Inicializa un array para almacenar las actualizaciones
            $updates = [];

            // Verifica cada precio fijo individualmente y agrega al array de actualizaciones si es mayor a 0
            if ($this->precioFijo1 > 0) {
                $updates['precio1'] = DB::raw('round(' . $this->precioFijo1 . ',2)');
            }

            if ($this->precioFijo2 > 0) {
                $updates['precio2'] = DB::raw('round(' . $this->precioFijo2 . ',2)');
            }

            if ($this->precioFijo3 > 0) {
                $updates['precio3'] = DB::raw('round(' . $this->precioFijo3 . ',2)');
            }

            // Solo realiza la actualización si hay al menos un precio fijo válido
            if (!empty($updates)) {
                $updates['updated_at'] = Carbon::now(); // Agrega la fecha de actualización

                $affected = DB::table('inventarios')
                    ->where('empresa_id', Auth::user()->empresa_id)
                    ->where($this->criterioFiltro, $this->datoFiltro)
                    // ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")
                    ->update($updates);
            } else {
                // Manejar el caso donde ninguno de los precios fijos es mayor a 0
                // Puedes lanzar una excepción, retornar un mensaje de error, etc.
                // throw new Exception("Ninguno de los precios fijos es mayor a 0.");
            }


        }else{

            $affected = DB::table('inventarios')
                        ->where('empresa_id', Auth::user()->empresa_id)
                        ->where($this->criterioFiltro,$this->datoFiltro)
                        // ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")
                        ->update(['precio1' => DB::raw('round(precio1 + ( precio1 * '.$this->porcentaje1.'/100),2)'),
                                    'precio2' => DB::raw('round(precio2 + ( precio2 * '.$this->porcentaje2.'/100),2)'),
                                    'precio3' => DB::raw('round(precio3 + ( precio3 * '.$this->porcentaje3.'/100),2)'),
                                    'updated_at'=> Carbon::now()]);
        }
               
        // dump($affected);

        if($this->imprimirReporte){

            $this->articulosEdicionMultiple = [
                'criterio'=>$this->criterioFiltro,
                'dato'=>$this->datoFiltro,
                
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

            'fijo1' => 'required|min:0|numeric',
            'fijo2' => 'required|min:0|numeric',
            'fijo3' => 'required|min:0|numeric',
        ]);

        $this->porcentaje1 = $this->porcentajePrecio1;
        $this->porcentaje2 = $this->porcentajePrecio2;
        $this->porcentaje3 = $this->porcentajePrecio3;

        $this->precioFijo1= $this->fijo1;
        $this->precioFijo2= $this->fijo2;
        $this->precioFijo3= $this->fijo3;

        $this->render();
    }
    


    public function render()
    {

        if($this->precioFijo){

            return view('livewire.inventario.edicion-multiple',[
                'inventario'=> DB::table('inventarios')
                                    ->select('*',DB::raw('round('.$this->precioFijo1.',2) as NuevoPrecio1'),
                                                    DB::raw('round('.$this->precioFijo2.',2) as NuevoPrecio2'),
                                                    DB::raw('round('.$this->precioFijo3.',2) as NuevoPrecio3'),)
                                    ->where('empresa_id', Auth::user()->empresa_id)
                                    ->where($this->criterioFiltro,$this->datoFiltro)
    
                                    // ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")        
                                    ->paginate(30),
            ])
            ->extends('layouts.app')
            ->section('main');

        }else{

            return view('livewire.inventario.edicion-multiple',[
                'inventario'=> DB::table('inventarios')
                                    ->select('*',DB::raw('round(precio1 + ( precio1 * '.$this->porcentaje1.'/100),2) as NuevoPrecio1'),
                                                    DB::raw('round(precio2 + ( precio2 * '.$this->porcentaje2.'/100),2) as NuevoPrecio2'),
                                                    DB::raw('round(precio3 + ( precio3 * '.$this->porcentaje3.'/100),2) as NuevoPrecio3'),)
                                    ->where('empresa_id', Auth::user()->empresa_id)
                                    ->where($this->criterioFiltro,$this->datoFiltro)
    
                                    // ->whereAny($this->arrayFiltros, 'LIKE', "%$this->datoBuscado%")        
                                    ->paginate(30),
            ])
            ->extends('layouts.app')
            ->section('main');
        }


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
