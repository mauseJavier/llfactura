<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 


use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Carbon;
use App\Models\Mesa;

class ImprimirMesaController extends Controller
{
    //

    public function imprimir(Mesa $mesa){

        // dd($mesa);

        $data = (json_decode( $mesa->data));

        // dd($data);

        $pdf = Pdf::loadView('PDF.pdfImprimirMesa',['mesa'=>$mesa,'data'=>$data]);
        $pdf->set_paper(array(0,0,250,(500)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        $nombreArchivo= 'Estado Mesa:'. $mesa->nombre.' '.Carbon::now()->format('Ymd').'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);   

    }
}
