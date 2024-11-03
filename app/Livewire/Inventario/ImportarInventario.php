<?php

namespace App\Livewire\Inventario;


use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

use App\Imports\InventarioImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Storage;
use App\Models\Inventario;


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




    public function importarCsv()
    {

        // dd($this->archivo);

        $rutaArchivo = $this->archivo->store('importaciones'); // Almacena el archivo temporalmente en 'storage/app/importaciones'
        // $this->importarCsv($rutaArchivo);


        // Verificar si el archivo existe
        if (!Storage::exists($rutaArchivo)) {
            return ['error' => 'El archivo no existe.'];
        }

        // Abrir el archivo para lectura
        $archivo = fopen(storage_path('app/' . $rutaArchivo), 'r');


        $fila = 1;
        if (($gestor = fopen(storage_path('app/' . $rutaArchivo), 'r')) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $numero = count($datos);
                echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;
                for ($c=0; $c < $numero; $c++) {
                    echo $datos[$c] . "<br />\n";
                }
            }
            fclose($gestor);
        }

        

        // Saltar la primera fila si es el encabezado
        $esPrimeraFila = true;



        while (($row = fgetcsv($archivo, 1000, ',')) !== false) {
            // Saltar la primera fila (encabezado)

            
            if ($esPrimeraFila) {
                $esPrimeraFila = false;
                continue;
            }
            
            dd($row);
            // Crear o actualizar el inventario
            // Inventario::updateOrCreate(
            //     [
            //         'codigo' => $row[$this->columnas['codigo']],
            //         'empresa_id' => $this->columnas['empresa_id']
            //     ],
            //     [
            //         'detalle'    => $row[$this->columnas['detalle']],
            //         'costo'      => isset($row[$this->columnas['costo']]) ? round(floatval($row[$this->columnas['costo']]), 2) : 0,
            //         'precio1'    => isset($row[$this->columnas['precio1']]) ? round(floatval($row[$this->columnas['precio1']]), 2) : 0,
            //         'precio2'    => isset($row[$this->columnas['precio2']]) ? round(floatval($row[$this->columnas['precio2']]), 2) : 0,
            //         'precio3'    => isset($row[$this->columnas['precio3']]) ? round(floatval($row[$this->columnas['precio3']]), 2) : 0,
            //         'porcentaje' => isset($row[$this->columnas['porcentaje']]) ? round(floatval($row[$this->columnas['porcentaje']]), 2) : 0,
            //         'iva'        => isset($row[$this->columnas['iva']]) ? round(floatval($row[$this->columnas['iva']]), 2) : $this->columnas['ivaDefecto'],
            //         'rubro'      => isset($row[$this->columnas['rubro']]) ? $row[$this->columnas['rubro']] : 'General',
            //         'proveedor'  => isset($row[$this->columnas['proveedor']]) ? $row[$this->columnas['proveedor']] : 'General',
            //         'marca'      => 'General',
            //         'pesable'    => isset($row[$this->columnas['pesable']]) ? $row[$this->columnas['pesable']] : 'no',
            //         'imagen'     => isset($row[$this->columnas['imagen']]) ? $row[$this->columnas['imagen']] : '',
            //     ]
            // );
        }

        // Cerrar el archivo
        fclose($archivo);

        // Eliminar el archivo después de la importación
        Storage::delete($rutaArchivo);

        return ['success' => 'Archivo importado y eliminado correctamente.'];
    }



 
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
