<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 


use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Carbon;
use App\Models\Comprobante;


// use App\Models\CuentaCorriente;
// use App\Models\Cliente;


class ReporteVentaUsuarioController extends Controller
{
    //

    public function imprimir(){


        $collection = Comprobante::select('comprobantes.idFormaPago as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporte'))
        ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
        ->whereBetween('comprobantes.created_at', [Carbon::now()->startOfDay(), Carbon::now()])
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
                ->whereBetween('comprobantes.created_at', [Carbon::now()->startOfDay(), Carbon::now()])
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


        $info=['titulo'=>'Reporte Diario:'. Auth()->user()->name,
                'usuario'=> Auth()->user()->name,
                'fechayhora'=>Carbon::now()->format('Y-m-d:H:i:s'),
                'totales'=>$totales,
            ];
        $pdf = Pdf::loadView('PDF.reporteUsuarioTicket',$info);
        $pdf->set_paper(array(0,0,250,(300)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        $nombreArchivo= 'Reporte:'. Auth()->user()->name.''.Carbon::now()->format('Ymd').'.pdf';
        return $pdf->download($nombreArchivo);
        // return $pdf->stream($nombreArchivo);   

    }
    
}
