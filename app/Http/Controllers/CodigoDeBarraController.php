<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf; 


class CodigoDeBarraController extends Controller
{
    public function imprimir(Request $request){

        $arrayInventario = $request->session()->get('arrayInventario');



        $pdf = Pdf::loadView('PDF.pdfCodigoBarra',compact('arrayInventario'));
        // $pdf->set_paper(array(0,0,250,300), 'portrait');

        // Forget a single key...
        $request->session()->forget('arrayInventario');


        $nombreArchivo= 'CodigoBarra.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);    

    }
}
