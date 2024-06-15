<?php

namespace App\Imports;

use App\Models\Stock;
use App\Models\Deposito;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StockImport implements ToCollection,WithHeadingRow
{

    protected $columnas;

    public function __construct($columnas)
    {
        $this->columnas = $columnas;
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) 
        {

            // dd($row['deposito']);

            // dd($this->columnas);
            // dd($row[$this->columnas['deposito']]);
    
            $deposito = Deposito::where('nombre', $row[$this->columnas['deposito']])
                            ->firstOr(function () use ($row) {
               
                return Deposito::create([
                        'nombre'=> $row[$this->columnas['deposito']],
                        'comentario'=> $row[$this->columnas['deposito']],
                        'empresa_id'=> Auth::user()->empresa_id,
                ]);

            
            });


            Stock::create([
                'codigo'=>$row[$this->columnas['codigo']],
                'detalle'=>$row[$this->columnas['detalle']],
                'deposito_id'=>$deposito->id,
                'stock'=> floatval($row[$this->columnas['stock']]) ,
                'comentario'=>'Importacion',
                'usuario'=>Auth::user()->name,
                'empresa_id'=>Auth::user()->empresa_id,
            ]);

            
        }
        



    }
}
