<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 


use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Carbon;
use App\Models\Comanda;

class ImprimirComandaController extends Controller
{
    //
    public function imprimir(Comanda $comanda){


        $data = (json_decode( $comanda->comanda));

        $pdf = Pdf::loadView('PDF.pdfComanda',['comanda'=>$comanda,'data'=>$data]);
        $pdf->set_paper(array(0,0,250,(300)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        $nombreArchivo= 'Comanda:'. Auth()->user()->name.''.Carbon::now()->format('Ymd').'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);   

    }
}
