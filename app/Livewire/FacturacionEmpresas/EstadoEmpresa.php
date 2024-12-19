<?php

namespace App\Livewire\FacturacionEmpresas;

use Livewire\Component;

use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


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


    public $ordenarPor='';

    public function mount(){

        $this->fechaDesde = Carbon::now()->format('Y-m-d');


    }


    public function ordenarActualizado(){

        $this->ordenarPor='updated_at';

    }

    public function exportarCSV(){


        $filename = 'empresas_facturacion_mes_actual.csv';

        // Abrir o crear el archivo CSV
        $handle = fopen($filename, 'w');

        // Agregar los encabezados al archivo CSV
        fputcsv($handle, ['ID Empresa', 'Nombre Empresa', 'Total Facturado','Vencimiento','Pago','Importe','Usuario','Comentario']);

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
                $empresa->pagoServicio == 'si' ?  'SI' : 'NO',
                $empresa->pagoMes,
                $empresa->usuarioPago,
                $empresa->comentario,




            ]);
        }

        // Cerrar el archivo CSV
        fclose($handle);

        // Mensaje de confirmación
        return response()->download($filename)->deleteFileAfterSend(true);

    }


    public function modificarFechaVencimientoPago(Empresa $empresa,$fecha,$pagoMes,$comentario,$pagoServicio){


        // dd($pagoServicio);
        $empresa->vencimientoPago=$fecha;
        $empresa->pagoMes=$pagoMes;
        $empresa->comentario=trim($comentario);
        $empresa->usuarioPago=Auth()->user()->name;

        $empresa->pagoServicio= $pagoServicio;


        $empresa->save();

        // dd($empresa);

        session()->flash('mensaje', 'Fecha Actualizada. '. $empresa->razonSocial .' Fecha: '. $fecha);


    }

    public function pagarEmpresa(Empresa $empresa,$fecha){



        $empresa->vencimientoPago="$fecha";
        $empresa->pagoServicio= $empresa->pagoServicio == 'si' ?  'no' : 'si';

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
              'pagoServicio'=>'no',
              'usuarioPago'=>'',
              'pagoMes'=>0,


            ]);


                session()->flash('mensaje', 'Riniciado mes actual. '. $affected);


    }

    public function render()
    {



        // Obtener todas las empresas
        $empresas = DB::table('empresas')
            ->where(function ($query) {
                $query->where('razonSocial', 'like', '%' . $this->datoBuscado . '%')
                    ->orWhere('titular', 'like', '%' . $this->datoBuscado . '%')
                    ->orWhere('cuit', 'like', '%' . $this->datoBuscado . '%');
            })
            // ->where('pagoServicio',$this->filtroPago)
            ->when($this->filtroPago, function ($query, $filtroPago) {
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


        if ($this->ordenarPor != '' and $this->ordenarPor == 'updated_at') {

            // Ordenar las empresas por total facturado de forma descendente
            usort($empresas, function($a, $b) {
                // return $b->totalFacturado <=> $a->totalFacturado;
                return strtotime($b->updated_at) <=> strtotime($a->updated_at);
            });

        }else{

            // Ordenar las empresas por total facturado de forma descendente
            usort($empresas, function($a, $b) {
                return $b->totalFacturado <=> $a->totalFacturado;
            });

        }

        // Ahora $empresas está ordenado por totalFacturado en orden descendente

        // dd($empresas);

        $totalPorUsuario = DB::table('empresas')
                ->select('usuarioPago', DB::raw('SUM(pagoMes) as totalPagos'), DB::raw('count(pagoMes) as cantidad'),)
                ->where(function ($query) {
                    $query->where('razonSocial', 'like', '%' . $this->datoBuscado . '%')
                        ->orWhere('titular', 'like', '%' . $this->datoBuscado . '%')
                        ->orWhere('cuit', 'like', '%' . $this->datoBuscado . '%');
                })
                ->when($this->filtroPago, function ($query, $filtroPago) {
                    $query->where('pagoServicio', $filtroPago);
                })
                ->groupBy('usuarioPago') // Agrupa por el campo usuarioPago
                ->get()
                ->toArray();
        
        // dd($totalPorUsuario);

        $total = DB::table('empresas')
        ->select(DB::raw('SUM(pagoMes) as total'),DB::raw('count(pagoMes) as totalCantidad'),)
        ->where(function ($query) {
            $query->where('razonSocial', 'like', '%' . $this->datoBuscado . '%')
                ->orWhere('titular', 'like', '%' . $this->datoBuscado . '%')
                ->orWhere('cuit', 'like', '%' . $this->datoBuscado . '%');
        })
        ->when($this->filtroPago, function ($query, $filtroPago) {
            $query->where('pagoServicio', $filtroPago);
        })
        ->get()
        ->toArray();

        // dd($total);

        return view('livewire.facturacion-empresas.estado-empresa',[
            'empresas'=>$empresas,
            'totalPorUsuario'=>$totalPorUsuario,
            'total'=>$total,


        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
