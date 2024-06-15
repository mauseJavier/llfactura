<?php

namespace App\Livewire\Stock;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

use App\Imports\StockImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Empresa;

class ImportarStock extends Component
{

    use WithFileUploads;

    public $empresa;

    public $CAMPOcodigo= 'codigo';
    public $CAMPOdetalle='detalle';
    public $CAMPOdeposito= 'deposito';
    public $CAMPOstock='stock'; 

    public $archivo;
 
    public function importarArchivo()
    {
        $validated = $this->validate([ 
            'CAMPOcodigo' => 'required',
            'CAMPOdetalle' => 'required',
            'archivo'=> 'required|file|mimes:xlsx',
        ]);




        // Crear una instancia de UsersImport pasando un parámetro adicional
        $import = new StockImport([
            'codigo'=> $this->CAMPOcodigo,
            'detalle'=> $this->CAMPOdetalle,
            'deposito'=> $this->CAMPOdeposito,
            'stock'=> $this->CAMPOstock,

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
        return view('livewire.stock.importar-stock')
        ->extends('layouts.app')
        ->section('main');
    }
}
