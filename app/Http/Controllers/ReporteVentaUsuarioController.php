<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 


use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Carbon;
use App\Models\Comprobante;
use App\Models\CierreCaja;
use App\Models\CuentaCorriente;



// use App\Models\CuentaCorriente;
// use App\Models\Cliente;


class ReporteVentaUsuarioController extends Controller
{
    //

    public function imprimir(Request $request){

        $cierreDia = CierreCaja::where('usuario_id',Auth()->user()->id)
        ->whereBetween('created_at', [$request->inicioTurno ,  $request->finTurno])
            // ->where('created_at',$request->finTurno->format('Y-m-d'))
            ->get();
        $sumaCierre = CierreCaja::where('usuario_id',Auth()->user()->id)
        ->whereBetween('created_at', [$request->inicioTurno ,  $request->finTurno])
        // ->where('created_at',$request->finTurno->format('Y-m-d'))
        ->sum('importe');

        $info=['titulo'=>'Reporte Diario:'. Auth()->user()->name,
            'usuario'=> Auth()->user()->name,
            'fechayhora'=>Carbon::now()->format('d-m-Y:H:i'),
            'inicioTurno'=>Carbon::parse($request->inicioTurno)->format('d-m-Y H:i'),
            'finTurno'=>Carbon::parse($request->finTurno)->format('d-m-Y H:i'),
            'cierreDia'=>$cierreDia,
            'sumaCierre'=>number_format($sumaCierre, 2, ',', '.'),
        ];

        $pdf = Pdf::loadView('PDF.reporteUsuarioTicket',$info);
        $pdf->set_paper(array(0,0,250,(300)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        $nombreArchivo= 'Reporte:'. Auth()->user()->name.''.Carbon::now()->format('Ymd').'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);   

    }

    //ESTE REPORTE AHORA SALE POR CIERRE DE CAJA 
    public function reporteCompleto(Request $request){

        // dd($request->inicioTurno);


        $collection = Comprobante::select('comprobantes.idFormaPago as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporte'))
        ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
        ->whereBetween('comprobantes.created_at', [$request->inicioTurno, $request->finTurno])
        ->where('comprobantes.usuario', 'like', '%' . Auth()->user()->name . '%')
        // ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
        // ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
        // ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
        ->groupBy('comprobantes.idFormaPago', 'forma_pagos.nombre')
        
        // Unir la segunda colección
        ->unionAll(
            Comprobante::select('comprobantes.idFormaPago2 as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeDos) as totalImporte'))
                ->join('forma_pagos', 'comprobantes.idFormaPago2', '=', 'forma_pagos.id')
                ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                ->whereBetween('comprobantes.created_at', [$request->inicioTurno, $request->finTurno])
                ->where('comprobantes.usuario', 'like', '%' . Auth()->user()->name . '%')
                // ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
                // ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
                // ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
                ->groupBy('comprobantes.idFormaPago2', 'forma_pagos.nombre')
        )
        ->get();

        // Procesar los resultados combinados en un único arreglo de totales
        $totales = [];

        foreach ($collection as $comprobante) {
            $idFormaPago = $comprobante->idFormaPago;
            $nombre = $comprobante->nombre;
            $totalImporte = $comprobante->totalImporte;

            if (!isset($totales[$idFormaPago])) {
                $totales[$idFormaPago] = [
                    'nombre' => $nombre,
                    'total' => 0,
                ];
            }

            $totales[$idFormaPago]['total'] += $totalImporte;

        }

        // Inicializa una variable para almacenar la suma total
            $sumaTotal = 0;
            $totalSoloEfectivo = 0;


            // Itera sobre cada subarray y suma los valores de la clave 'total'
            foreach ($totales as $subarray) {
                if (isset($subarray['total'])) {
                    $sumaTotal += $subarray['total'];

                    if($subarray['nombre'] == 'Efectivo'){
                        $totalSoloEfectivo += $subarray['total'];
                    }
                }
            }


        // dd(number_format($sumaTotal, 2, ',', '.'));

        // SUMAR EL COMBRO DE CUENTAS CORRIENTES 

        $cobroCuentasCorrientes=0;
        $cobroCC = CuentaCorriente::where('usuario',Auth()->user()->name)
        // ->whereDate('created_at', $this->fechaCierre)
        ->whereBetween('created_at', [$request->inicioTurno, $request->finTurno])
        ->get();

        foreach ($cobroCC as $key => $value) {
            # code...
            $cobroCuentasCorrientes += $value->haber ;
        }


        $sumaCierre = CierreCaja::where('usuario_id',Auth()->user()->id)
        // ->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
        ->whereBetween('created_at', [$request->inicioTurno , $request->finTurno])

            // ->where('created_at',Carbon::now()->format('Y-m-d'))
            ->sum('importe');

            $cierres = CierreCaja::where('usuario_id',Auth()->user()->id)
            // ->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                ->whereBetween('created_at', [$request->inicioTurno , $request->finTurno])
                ->get();


        $info=['titulo'=>'Reporte Diario:'. Auth()->user()->name,
                'usuario'=> Auth()->user()->name,
                'fechayhora'=>Carbon::now()->format('d-m-Y H:i'),
                'inicioTurno'=>Carbon::parse($request->inicioTurno)->format('d-m-Y H:i'),
                'finTurno'=>Carbon::parse($request->finTurno)->format('d-m-Y H:i'),
                'totales'=>$totales,
                'cierres'=>$cierres,

                'sumaTotal'=>number_format($sumaTotal, 2, ',', '.'),

                'sumaCierre'=>number_format($sumaCierre, 2, ',', '.'),
                'totalSoloEfectivo'=>number_format($totalSoloEfectivo, 2, ',', '.'),
                'cobroCuentasCorrientes'=>number_format($cobroCuentasCorrientes, 2, ',', '.'),
                'diferencia'=>number_format($sumaCierre -( $totalSoloEfectivo + $cobroCuentasCorrientes), 2, ',', '.'),
            ];

            
        $pdf = Pdf::loadView('PDF.pdfReporteCierreCompleto',$info);
        $pdf->set_paper(array(0,0,250,(400)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        $nombreArchivo= 'Reporte:'. Auth()->user()->name.''.Carbon::now()->format('Ymd').'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);   

    }

    
}
