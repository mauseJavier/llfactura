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
use App\Models\Rubro; 
use App\Models\Proveedor;
use App\Models\ListaPrecio; 
use App\Models\Marca; 




use App\Models\Empresa;

class ImportarInventario extends Component
{

    use WithFileUploads;

    public $empresa;

    #[Validate('required|mimes:csv')]
    public $archivo;

    public $procesados;




    public function importarCsv()
    {

        // dd($this->archivo);

        // 'photo' => 'mimes:jpg,bmp,png'

        $this->validate();

        $empresa = Empresa::find(auth()->user()->empresa_id);

        $rutaArchivo = $this->archivo->store('importaciones'); // Almacena el archivo temporalmente en 'storage/app/importaciones'
        // $this->importarCsv($rutaArchivo);


        // Verificar si el archivo existe
        if (!Storage::exists($rutaArchivo)) {
            return ['error' => 'El archivo no existe.'];
        }

        // Abrir el archivo para lectura
        $archivo = fopen(storage_path('app/' . $rutaArchivo), 'r');


        // $fila = 1;
        // if (($gestor = fopen(storage_path('app/' . $rutaArchivo), 'r')) !== FALSE) {
        //     while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        //         $numero = count($datos);
        //         echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
        //         $fila++;
        //         for ($c=0; $c < $numero; $c++) {
        //             echo $datos[$c] . "<br />\n";
        //         }
        //     }
        //     fclose($gestor);
        // }

        

        // Saltar la primera fila si es el encabezado
        $esPrimeraFila = true;



        while (($row = fgetcsv($archivo, 1000, ',')) !== false) {
            // Saltar la primera fila (encabezado)

            
            if ($esPrimeraFila) {
                $esPrimeraFila = false;
                continue;
            }
            
            // dump(str_replace(',','.',$row[2]));
            // dump($row[7]);
            // dump($row[7] !='' ? round(floatval(str_replace(',','.',$row[7])), 2) : 'hola');


            // Crear o actualizar el inventario


            if($row[0]!='' and $row[1]!=''){

               $nuevo= Inventario::updateOrCreate(
                    [
                        'codigo' => $row[0],
                        'empresa_id' => auth()->user()->empresa_id,
                    ],
                    [
                        'detalle'    => $row[1],
    
                        'costo'      => $row[2]!='' ? round(floatval(str_replace(',','.',$row[2])), 2) : 0,
    
                        'precio1'    =>  $row[3]!='' ? round(floatval(str_replace(',','.',$row[3])), 2) : 0,
                        'precio2'    =>  $row[4]!='' ? round(floatval(str_replace(',','.',$row[4])), 2) : 0,
                        'precio3'    =>  $row[5]!='' ? round(floatval(str_replace(',','.',$row[5])), 2) : 0,
                        
                        'porcentaje' =>  $row[6]!='' ? round(floatval(str_replace(',','.',$row[6])), 2) : 0,
    
                        'iva'        =>  $row[7]!='' ? round(floatval(str_replace(',','.',$row[7])), 2) : $empresa->ivaDefecto,
    
                        'rubro'      => $row[8]!='' ? $row[8] : 'General',
                        'proveedor'  => $row[9]!='' ? $row[9] : 'General',
                        'marca'      => $row[10]!='' ? $row[10] : 'General',
    
                        'pesable'    => strtolower($row[11]) == 'si' ? 'si' : 'no',
                        'imagen'     => $row[12]!='' ? $row[12] : '',
                    ]
                );


                if($row[8]!=''){

                    $r = Rubro::updateOrCreate(
                        ['nombre' => $row[8], ],
                        ['empresa_id' => auth()->user()->empresa_id,]
                    );
                }
    
                if($row[9]!=''){
    
                    $p = Proveedor::updateOrCreate(
                        ['nombre' => $row[9], ],
                        ['empresa_id' => auth()->user()->empresa_id,]
                    );
    
                }

                if($row[10]!=''){
    
                    $p = Marca::updateOrCreate(
                        ['nombre' => $row[10] ,
                         'empresa_id' => auth()->user()->empresa_id],
                        [
                         'comentario' => 'Marca de: '.$row[10],
                        ]
                    );
    
                }
    
    
                if($row[6]!=''){
    
                    $p = ListaPrecio::updateOrCreate(
                        ['nombre' => 'Lista al: '. $row[6] ,
                         'empresa_id' => auth()->user()->empresa_id],
                        [
                         'porcentaje' =>round( str_replace(',','.',$row[6]) ,2) ,
                        ]
                    );
    
                }



            }else{


            }

           
            $this->procesados[]=$nuevo;
        }

        // Cerrar el archivo
        fclose($archivo);

        // Eliminar el archivo después de la importación
        Storage::delete($rutaArchivo);

        session()->flash('mensaje', 'Importacion Correcta.');
    }



 
    // public function importarArchivo()
    // {
    //     $validated = $this->validate([ 
    //         'CAMPOcodigo' => 'required',
    //         'CAMPOdetalle' => 'required',
    //         // 'archivo'=> 'required|file|mimes:xlsx',
    //     ]);




    //     // Crear una instancia de UsersImport pasando un parámetro adicional
    //     $import = new InventarioImport([
    //         'empresa_id'=> $this->empresa->id,
    //         'ivaDefecto'=> $this->empresa->ivaDefecto,
    //         'codigo'=>$this->CAMPOcodigo,
    //         'detalle'=>$this->CAMPOdetalle,
    //         'costo'=>$this->CAMPOcosto,
    //         'precio1'=>$this->CAMPOprecio1,
    //         'precio2'=>$this->CAMPOprecio2,
    //         'precio3'=>$this->CAMPOprecio3,
    //         'iva'=>$this->CAMPOiva,
    //         'rubro'=>$this->CAMPOrubro,
    //         'proveedor'=>$this->CAMPOproveedor,

    //         'pesable'=>$this->CAMPOpesable,
    //         'imagen'=>$this->CAMPOimagen,
    //         'stock' => $this->CAMPOstock,
    //         'deposito' => $this->CAMPOdeposito,
    //         'porcentaje' => $this->CAMPOporcentaje,
    //         'nombreLista' => $this->CAMPOnombreLista,


    //     ]);

    //     Excel::import($import, $this->archivo);


    //     dump('TODO OK');

    //     // $array = Excel::toArray(new InventarioImport, $this->archivo);

    //     // dump($array);

    //     // foreach ($array as $key => $value) {
    //     //     foreach ($value as $key1 => $fila) {
                
    //     //         if($this->costo){
    //     //             dump($fila[$this->costo]);
    //     //         }
    //     //     }
    //     // }
    // }

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
