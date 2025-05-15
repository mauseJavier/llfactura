<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\PdfComprobanteGenerar;



class PresupuestoController extends Controller
{
    //

        public function imprimir($presupuesto_id , $formato = 'A4')
        {
            $PdfComprobanteGenerar = new PdfComprobanteGenerar();
            return $PdfComprobanteGenerar->imprimirPresupuesto($presupuesto_id, $formato);
        }

        public function presupuestoBase64($presupuesto_id , $formato = 'A4')
        {
            $PdfComprobanteGenerar = new PdfComprobanteGenerar();
            return $PdfComprobanteGenerar->obtenerPdfBase64Presupuesto($presupuesto_id, $formato,1);
        }

}
