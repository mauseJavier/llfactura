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



        $totales = Comprobante::select('comprobantes.idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.total) as sumTotal'))
                                        ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
                                        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                                        ->where('comprobantes.created_at', '>=', Carbon::now()->startOfDay())
                                        ->where('comprobantes.created_at', '<=', Carbon::now())
                                        ->where('comprobantes.usuario', 'like', '%' . Auth()->user()->name . '%')

                                        // ->when($this->tipoComp, function ($query, $tipoComp) {
                                        //     return $query->where('comprobantes.tipoComp', $tipoComp);
                                        // })
                                // ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
                                // return $query->where('numero', '=',$numeroComprobanteFiltro);
                                // })

                                // ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
                                // return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
                                // })


                                        ->groupBy('comprobantes.idFormaPago','forma_pagos.nombre')
                                        ->get();
        // dd($totales);

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
