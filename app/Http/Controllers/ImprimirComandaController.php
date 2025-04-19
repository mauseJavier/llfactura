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
    
        // Verificamos que la mesa esté cargada
        // dd($comanda->mesa);
    
        $pdf = Pdf::loadView('PDF.pdfComanda', [
            'comanda' => $comanda,
            'data' => $data,
        ]);
    
        $pdf->set_paper([0, 0, 250, 300], 'portrait');
    
        $nombreArchivo = 'Comanda-' . auth()->user()->name . '-' . now()->format('Ymd') . '.pdf';
    
        return $pdf->stream($nombreArchivo);
    }
    
}
