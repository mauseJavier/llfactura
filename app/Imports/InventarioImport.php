<?php

namespace App\Imports;

use App\Models\Inventario;  
use App\Models\Rubro; 
use App\Models\Proveedor;
use App\Models\ListaPrecio; 



use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class InventarioImport implements ToCollection,WithHeadingRow
{

    protected $columnas;

    public function __construct($columnas)
    {
        $this->columnas = $columnas;
    }

    public function collection(Collection $rows)
    {

        // $table->string('codigo');
        // $table->string('detalle');
        // $table->double('costo')->default(0);
        // $table->double('precio1')->default(0);
        // $table->double('precio2')->default(0);
        // $table->double('precio3')->default(0);
        // $table->double('iva')->default(21);
        // $table->string('rubro')->default('General')->nullable();
        // $table->string('proveedor')->default('General')->nullable();
        // $table->enum('pesable', ['si', 'no'])->default('no');
        // $table->string('imagen')->nullable();
        // $table->unsignedInteger('empresa_id')->default(1);

        Validator::make($rows->toArray(), [
            'codigo' => 'string|max:255',
            'detalle' => 'string|max:255',
        ])->validate();


        foreach ($rows as $row) 
        {

            // dump( $row);

            // dd();

            $nuevoInventario = Inventario::updateOrCreate(
                ['codigo' => $row[$this->columnas['codigo']], 
                    'empresa_id' => $this->columnas['empresa_id']],
                [                
                    'detalle'    => $row[$this->columnas['detalle']], 
                    'costo'     =>(isset($row[$this->columnas['costo']])) ? round( floatval($row[$this->columnas['costo']]),2)  : 0 , 
                    'precio1'   => (isset($row[$this->columnas['precio1']])) ? round( floatval($row[$this->columnas['precio1']]),2)  : 0 ,
                    'precio2'   => (isset($row[$this->columnas['precio2']])) ? round( floatval($row[$this->columnas['precio2']]),2)  : 0 ,
                    'precio3'   => (isset($row[$this->columnas['precio3']])) ? round( floatval($row[$this->columnas['precio3']]),2)  : 0 ,
                    'porcentaje'   => (isset($row[$this->columnas['porcentaje']])) ? round( floatval($row[$this->columnas['porcentaje']]),2)  : 0 ,


                    'iva'       => (isset($row[$this->columnas['iva']])) ? round( floatval($row[$this->columnas['iva']]),2)  : $this->columnas['ivaDefecto'] ,

                    'rubro'    => (isset($row[$this->columnas['rubro']])) ? $row[$this->columnas['rubro']] : 'General' ,
                    'proveedor'    => (isset($row[$this->columnas['proveedor']])) ?  $row[$this->columnas['proveedor']] : 'General' ,
                    'marca'=>'General',

                    'pesable'    => (isset($row[$this->columnas['pesable']])) ?  $row[$this->columnas['pesable']] : 'no' ,
                    'imagen'    => (isset($row[$this->columnas['imagen']])) ?  $row[$this->columnas['imagen']] : '' ,
                ]
            );

            if(isset($row[$this->columnas['rubro']])){

                $r = Rubro::updateOrCreate(
                    ['nombre' => $row[$this->columnas['rubro']], ],
                    ['empresa_id' => $this->columnas['empresa_id'],]
                );
            }

            if(isset($row[$this->columnas['proveedor']])){

                $p = Proveedor::updateOrCreate(
                    ['nombre' => $row[$this->columnas['proveedor']], ],
                    ['empresa_id' => $this->columnas['empresa_id'],]
                );

            }

            $nombreLista = (isset($row[$this->columnas['nombreLista']])) ?  $row[$this->columnas['nombreLista']] : $row[$this->columnas['porcentaje']] ;

            if(isset($row[$this->columnas['porcentaje']])){

                $p = ListaPrecio::updateOrCreate(
                    ['nombre' => $nombreLista ,
                     'empresa_id' => $this->columnas['empresa_id']],
                    [
                     'porcentaje' => $row[$this->columnas['porcentaje']],
                    ]
                );

            }




        }
    }
}





// class InventarioImport implements ToModel,WithValidation, WithHeadingRow, SkipsOnFailure
// {
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */

//     use Importable, SkipsFailures;

//     protected $columnas;

//     public function __construct($columnas)
//     {
//         $this->columnas = $columnas;
//     }


//     public function model(array $row)
//     {


//         return new Inventario([
//             //
//             'codigo'     => $row[$this->columnas['codigo']],
//             'detalle'    => $row[$this->columnas['detalle']], 
//             'costo'    => round( $row[$this->columnas['costo']],2), 
//         ]);
//     }

//     public function rules(): array
//     {
//         return [

//             'costo' => 'numeric',
//             // 'costo' => Rule::in(['numeric']),
//             // 'precio1' => 'decimal:2'

//              // Above is alias for as it always validates in batches
//             //  '*.email' => Rule::in(['patrick@maatwebsite.nl']),
//         ];
//     }




// }
