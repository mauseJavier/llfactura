<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class EdicionMultipleExport implements FromCollection
{
    public function __construct($datos)
    {
        $this->datos = $datos;

    }

    public function collection()
    {
        return $this->datos->all();
    }
}



