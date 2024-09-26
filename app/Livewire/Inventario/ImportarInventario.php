<?php

namespace App\Livewire\Inventario;


use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

use App\Imports\InventarioImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Empresa;

class ImportarInventario extends Component
{

    use WithFileUploads;

    public $empresa;

    public $CAMPOcodigo= 'codigo';
    public $CAMPOdetalle='detalle';
    public $CAMPOcosto='costo';
    public $CAMPOprecio1='precio1';
    public $CAMPOprecio2;
    public $CAMPOprecio3;
    public $CAMPOiva='iva';
    public $CAMPOrubro='rubro';
    public $CAMPOproveedor='proveedor';
    public $CAMPOpesable;
    public $CAMPOimagen;
    public $CAMPOstock;
    public $CAMPOdeposito;
    public $CAMPOporcentaje="porcentaje";
    public $CAMPOnombreLista="nombrelista";
 

    public $archivo;
 
    public function importarArchivo()
    {
        $validated = $this->validate([ 
            'CAMPOcodigo' => 'required',
            'CAMPOdetalle' => 'required',
            // 'archivo'=> 'required|file|mimes:xlsx',
        ]);




        // Crear una instancia de UsersImport pasando un parámetro adicional
        $import = new InventarioImport([
            'empresa_id'=> $this->empresa->id,
            'ivaDefecto'=> $this->empresa->ivaDefecto,
            'codigo'=>$this->CAMPOcodigo,
            'detalle'=>$this->CAMPOdetalle,
            'costo'=>$this->CAMPOcosto,
            'precio1'=>$this->CAMPOprecio1,
            'precio2'=>$this->CAMPOprecio2,
            'precio3'=>$this->CAMPOprecio3,
            'iva'=>$this->CAMPOiva,
            'rubro'=>$this->CAMPOrubro,
            'proveedor'=>$this->CAMPOproveedor,
            'pesable'=>$this->CAMPOpesable,
            'imagen'=>$this->CAMPOimagen,
            'stock' => $this->CAMPOstock,
            'deposito' => $this->CAMPOdeposito,
            'porcentaje' => $this->CAMPOporcentaje,
            'nombreLista' => $this->CAMPOnombreLista,


        ]);

        Excel::import($import, $this->archivo);


        dump('TODO OK');

        // $array = Excel::toArray(new InventarioImport, $this->archivo);

        // dump($array);

        // foreach ($array as $key => $value) {
        //     foreach ($value as $key1 => $fila) {
                
        //         if($this->costo){
        //             dump($fila[$this->costo]);
        //         }
        //     }
        // }
    }

    public function mount()
    {
        $this->empresa = Empresa::find( Auth::user()->empresa_id);

    }

    public function render()
    {
        return view('livewire.inventario.importar-inventario',
        [     
            
        ])        
        ->extends('layouts.app')
        ->section('main');
    }
}
