<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf; 

use Picqer\Barcode\BarcodeGeneratorPNG;



// array:3 [▼ // app/Http/Controllers/CodigoDeBarraController.php:19
//   0 => array:5 [▼
//     "id" => 228272
//     "codigo" => "Nuevo Articulo"
//     "detalle" => "nuevo nuevo"
//     "precio" => 100
//     "tipo" => "Code128"
//   ]
//   1 => array:5 [▼
//     "id" => 228273
//     "codigo" => "VAR002"
//     "detalle" => "Gomera Plastica c/empuñadura Anatomica "
//     "precio" => 27259.39
//     "tipo" => "Code128"
//   ]
//   2 => array:5 [▼
//     "id" => 228274
//     "codigo" => "CFR1252"
//     "detalle" => "Regaton PVC Redondo 11/4" 32mm"
//     "precio" => 115.46
//     "tipo" => "Code128"
//   ]
// ]


class CodigoDeBarraController extends Controller
{
    public function imprimir(Request $request){

        $arrayInventario = $request->session()->get('arrayInventario');


        if(isset($arrayInventario) AND count($arrayInventario)>0){

            foreach ($arrayInventario as $key => $articulo) {
                // Verificar si el artículo tiene un código válido
                if (isset($articulo['codigo']) && !empty($articulo['codigo'])) {
                    // Generar el código de barras
                    $generator = new BarcodeGeneratorPNG();
                    
                    if (isset($articulo['tipo']) && $articulo['tipo'] === 'Code128') {
                        // Si el tipo es Code128, usar ese tipo
                        // $generator = new BarcodeGeneratorPNG();
                        $barcode = base64_encode($generator->getBarcode($articulo['codigo'], $generator::TYPE_CODE_128));
                    } else {
                        // Si no se especifica tipo, usar el tipo por defecto
                        // $generator = new BarcodeGeneratorPNG();
                        $barcode = base64_encode($generator->getBarcode($articulo['codigo'], $generator::TYPE_EAN_13));

                    }
                    // Agregar el código de barras al artículo
                    $arrayInventario[$key]['barcode'] = $barcode;
                } else {
                    // Si no hay código, asignar un valor nulo o un mensaje
                    $arrayInventario[$key]['barcode'] = null; // o 'Código no disponible'
                }
            }


            // dd($arrayInventario);

            $pdf = Pdf::loadView('PDF.pdfCodigoBarra',compact('arrayInventario'));
            // $pdf->set_paper(array(0,0,250,300), 'portrait');
    
            // Forget a single key...
            $request->session()->forget('arrayInventario');
    
    
            $nombreArchivo= 'CodigoBarra.pdf';
            // return $pdf->download($nombreArchivo);
            return $pdf->stream($nombreArchivo);    
        }else{
            return redirect()->route('codigoBarra');
        }


    }
}
