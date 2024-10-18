<?php

namespace App\Livewire\FacturacionEmpresas;

use Livewire\Component;

use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use App\Models\Comprobante;
use App\Models\Empresa;

use Carbon\Carbon;


class EstadoEmpresa extends Component
{
    use WithPagination;

    public $razonSocial='';
    public $fechaDesde ;

    public function mount(){

        $this->fechaDesde = Carbon::now()->format('Y-m-d');


    }

    public function exportarCSV(){


        $filename = 'empresas_facturacion_mes_actual.csv';

        // Abrir o crear el archivo CSV
        $handle = fopen($filename, 'w');

        // Agregar los encabezados al archivo CSV
        fputcsv($handle, ['ID Empresa', 'Nombre Empresa', 'Total Facturado']);

        // Ejecutar la consulta para obtener los datos
        $empresas = DB::table('empresas')
            ->join('comprobantes', 'empresas.id', '=', 'comprobantes.empresa_id')
            ->select(
                'empresas.id',
                'empresas.razonSocial',
                DB::raw('SUM(CASE WHEN comprobantes.fecha BETWEEN "' . Carbon::now()->startOfMonth() . '" AND "' . Carbon::now()->endOfMonth() . '" THEN comprobantes.total ELSE 0 END) as totalFacturado')
            )
            ->groupBy('empresas.id')
            ->orderBy('totalFacturado','DESC')
            ->get();

        // Escribir los datos de la consulta en el archivo CSV
        foreach ($empresas as $empresa) {
            fputcsv($handle, [
                $empresa->id,
                $empresa->razonSocial,
                $empresa->totalFacturado
            ]);
        }

        // Cerrar el archivo CSV
        fclose($handle);

        // Mensaje de confirmación
        return response()->download($filename)->deleteFileAfterSend(true);

    }

    public function render()
    {
        
        return view('livewire.facturacion-empresas.estado-empresa',[
            'empresas'=> 
            $empresas = DB::table('empresas')
                ->join('comprobantes', 'empresas.id', '=', 'comprobantes.empresa_id')
                ->select(
                    'empresas.*',
                    DB::raw('SUM(CASE WHEN comprobantes.fecha BETWEEN "' . Carbon::now()->startOfMonth() . '" AND "' . Carbon::now()->endOfMonth() . '" THEN comprobantes.total ELSE 0 END) as totalFacturado')
                )
                ->groupBy('empresas.id')
                ->orderBy('totalFacturado','DESC')
                ->paginate(100)
            
        ,

        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
