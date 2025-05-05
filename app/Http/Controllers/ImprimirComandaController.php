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
    public function imprimir(Comanda $comanda)
    {
        // Cargar la relación con la mesa
        $comanda->load('mesa');

        $data = json_decode($comanda->comanda);

        // Calcular la altura dinámica del papel en función de la cantidad de artículos
        $alturaPorArticulo = 20; // Altura estimada por artículo en puntos (1 punto = 1/72 pulgadas)
        $alturaBase = 150; // Altura base para encabezados y pies de página
        $alturaTotal = $alturaBase + (count($data) * $alturaPorArticulo);

        $pdf = Pdf::loadView('PDF.pdfComanda', [
            'comanda' => $comanda,
            'data' => $data,
        ]);

        // Ajustar el tamaño del papel a 80mm de ancho y altura dinámica
        $pdf->setPaper([0, 0, 226.77, $alturaTotal], 'portrait') // 80mm = 226.77 puntos
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin-top' => 0,
                'margin-bottom' => 0,
                'margin-left' => 0,
                'margin-right' => 0,
            ]);

        $nombreArchivo = 'Comanda-' . auth()->user()->name . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->stream($nombreArchivo);
    }
    
}
