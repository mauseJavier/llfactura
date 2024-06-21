<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf; 


class InventarioController extends Controller
{
    //

    public function remporteEdicionMultiple(Request $request){

        $filtros = $request->session()->get('articulosEdicionMultiple');

        // dd($filtros);
        // "filtros" => array:5 [▼
        //         0 => "codigo"
        //         1 => "detalle"
        //         2 => "rubro"
        //         3 => "proveedor"
        //         4 => "marca"
        //     ]
        //     "datoBuscado" => ""
        //     ]


        $datos = DB::table('inventarios')
        ->select('*')
        ->where('empresa_id', Auth::user()->empresa_id)
        ->whereAny($filtros['filtros'], 'LIKE', '%'.$filtros['datoBuscado'].'%')
        ->get();


        $pdf = Pdf::loadView('PDF.pdfEdicionMultiple',compact('datos'));        

        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        // Forget a single key...
        $request->session()->forget('articulosEdicionMultiple');

        $nombreArchivo= 'ReporteEdicionMultiple.pdf';
        return $pdf->download($nombreArchivo);
        // return $pdf->stream($nombreArchivo);   



    }
}
