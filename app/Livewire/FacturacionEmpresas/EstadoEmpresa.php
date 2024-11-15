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

    public $datoBuscado;
    public $filtroPago='';

    public function mount(){

        $this->fechaDesde = Carbon::now()->format('Y-m-d');


    }

    public function exportarCSV(){


        $filename = 'empresas_facturacion_mes_actual.csv';

        // Abrir o crear el archivo CSV
        $handle = fopen($filename, 'w');

        // Agregar los encabezados al archivo CSV
        fputcsv($handle, ['ID Empresa', 'Nombre Empresa', 'Total Facturado','Vencimiento','Pago']);

        // Obtener todas las empresas
        $empresas = DB::table('empresas')->get()->toArray();

        // Recorrer cada empresa para calcular el total facturado del mes actual
        foreach ($empresas as $key => $empresa) {
            $totalFacturado = DB::table('comprobantes')
                ->where('empresa_id', $empresa->id)
                ->whereBetween('fecha', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->sum('total');

            $empresas[$key]->totalFacturado = $totalFacturado;
        }

        // Ordenar las empresas por total facturado de forma descendente
        usort($empresas, function($a, $b) {
            return $b->totalFacturado <=> $a->totalFacturado;
        });

        // Escribir los datos de la consulta en el archivo CSV
        foreach ($empresas as $empresa) {
            fputcsv($handle, [
                $empresa->id,
                $empresa->razonSocial,
                $empresa->totalFacturado,
                $empresa->vencimientoPago,
                $empresa->pagoServicio == 1 ?  'SI' : 'NO',

            ]);
        }

        // Cerrar el archivo CSV
        fclose($handle);

        // Mensaje de confirmación
        return response()->download($filename)->deleteFileAfterSend(true);

    }


    public function modificarFechaVencimientoPago(Empresa $empresa,$fecha){


        $empresa->vencimientoPago=$fecha;
        $empresa->save();


        session()->flash('mensaje', 'Fecha Actualizada. '. $empresa->razonSocial .' Fecha: '. $fecha);


    }

    public function pagarEmpresa(Empresa $empresa,$fecha){



        $empresa->vencimientoPago=$fecha;
        $empresa->pagoServicio= $empresa->pagoServicio == 1 ?  0 : 1;

        $empresa->save();

        $this->render();

        session()->flash('mensaje', 'Pago Realizado. '. $empresa->razonSocial .' Fecha: '. $fecha);


    }


    public function reiniciarPagos(){

        // Crea una instancia de Carbon para la fecha actual
            $fechaActual = Carbon::now();

            // Modifica el día a 10
            $dia10 = $fechaActual->setDay(10);

            // Formatea la fecha al formato deseado: '2024-11-01'
            $fechaFormateada = $dia10->format('Y-m-d');

            $affected = DB::table('empresas')
              ->update(['vencimientoPago' => $fechaFormateada,
              'pagoServicio'=>0]);


                session()->flash('mensaje', 'Riniciado mes actual. '. $affected);


    }

    public function render()
    {


        $filtroPago=$this->filtroPago;

        // Obtener todas las empresas
        $empresas = DB::table('empresas')
            ->where(function ($query) {
                $query->where('razonSocial', 'like', '%' . $this->datoBuscado . '%')
                    ->orWhere('titular', 'like', '%' . $this->datoBuscado . '%')
                    ->orWhere('cuit', 'like', '%' . $this->datoBuscado . '%');
            })
            ->when($filtroPago, function ($query, $filtroPago) {
                $query->where('pagoServicio', $filtroPago);
            })
            ->get()
            ->toArray();

   

        // Recorrer cada empresa para calcular el total facturado del mes actual
        foreach ($empresas as $key => $empresa) {
            $totalFacturado = DB::table('comprobantes')
                ->where('empresa_id', $empresa->id)
                ->whereBetween('fecha', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->sum('total');

            $empresas[$key]->totalFacturado = $totalFacturado;
        }

        // Ordenar las empresas por total facturado de forma descendente
        usort($empresas, function($a, $b) {
            return $b->totalFacturado <=> $a->totalFacturado;
        });

        // Ahora $empresas está ordenado por totalFacturado en orden descendente
        
        // dd($empresas);

        return view('livewire.facturacion-empresas.estado-empresa',[
            'empresas'=> 
                    $empresas            
                ,

                'usuario'=>'usuario'

        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
