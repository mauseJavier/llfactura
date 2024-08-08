<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf; 


use App\Models\CuentaCorriente;
use App\Models\Cliente;


class ReciboPdfController extends Controller
{
    public function imprimir(CuentaCorriente $recibo_id){
        // dd($recibo_id);
        // "id" => 3
        // "empresa_id" => 1
        // "cliente_id" => 1
        // "comprobante_id" => 0
        // "tipo" => "pago"
        // "comentario" => "pago alfredo"
        // "debe" => 0.0
        // "haber" => 200.0
        // "saldo" => 255.95
        // "usuario" => "MAUSE LLFACTURA"
        // "created_at" => "2024-08-04 12:56:56"
        // "updated_at" => "2024-08-04 12:56:56"

        $cliente = Cliente::find($recibo_id->cliente_id);

        $pdf = Pdf::loadView('PDF.pdfReciboPdf',compact('recibo_id','cliente'));
        $pdf->set_paper(array(0,0,250,300), 'portrait');

        $nombreArchivo= 'Recibo de Pago '.$cliente->razonSocial.'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);    

    }
}
