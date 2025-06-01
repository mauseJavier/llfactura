<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth; 


use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Carbon;
// use App\Models\Mesa;
use App\Models\Empresa;
use App\Models\OrdenCompra;


class ImprimirOrdenCompra extends Controller
{
    //


    // crear el metodo reImprimirOrdenCompra igual al de imprimirOrdenCompra pero que tome los datos de la base de datos y no de la session con el id de la orden de compra
    public function reImprimirOrdenCompra($id)
    {

        $empresa = Empresa::find(Auth::user()->empresa_id);
        // $ordenDeCompra = session()->get('ordenDeCompra', []);

        $ordenDeCompra = OrdenCompra::where('id',$id)->where('empresa_id',Auth::user()->empresa_id)->get()->first();

        
        // dd($ordenDeCompra['proveedor']);
        // dd($ordenDeCompra);
        // "codigo" => "6379808989577"
        // "detalle" => "Aluminio"
        // "rubro" => "Alimentación"
        // "proveedor" => "Vital"
        // "marca" => "Ford"
        // "cantidad" => "2"
        // "costo" => 340.35
        // "subTotal" => 680.7
            $total = 0;
    
            if (isset($ordenDeCompra['articulos'])) {

                foreach ($ordenDeCompra['articulos'] as $item) {
                    $total += $item['subTotal'];
                }
            }else{
                $ordenDeCompra['articulos'] = [];
            }

                $proveedor['nombre'] = $ordenDeCompra['proveedor']; 
                $proveedor['cuit'] = $ordenDeCompra['cuit_proveedor'];
                $proveedor['direccion'] = $ordenDeCompra['direccion_proveedor'];
                $proveedor['email'] = $ordenDeCompra['email_proveedor'];
                $proveedor['telefono'] = $ordenDeCompra['telefono'];


                // $ordenDeCompra['proveedor']['telefono'] = $ordenDeCompra->telefono;

                // $ordenDeCompra['proveedor']['direccion'] = '';
                // $ordenDeCompra['proveedor']['email'] = '';
                // $ordenDeCompra['proveedor']['cuit'] = '';
                // $ordenDeCompra['proveedor']['telefono'] = '';
    
            

            $data = [
                'total' => $total,
                'proveedor' => 'Vital',
                'fecha' => Carbon::now()->format('d/m/Y'),
                'usuario' => Auth::user()->name,
                'empresa' => $empresa,

            ];
        $pdf = Pdf::loadView('PDF.pdfOrdenDeCompra',['ordenDeCompra'=>$ordenDeCompra,'data' => $data,'proveedor'=>$proveedor]);
        // $pdf->set_paper(array(0,0,250,(500)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));
        $nombreArchivo= 'Orden de Compra: '.Carbon::now()->format('Ymd').'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);
    }

    public function imprimirOrdenCompra(Request $request)
    {

        $empresa = Empresa::find(Auth::user()->empresa_id);
        // $ordenDeCompra = session()->get('ordenDeCompra', []);

        $ordenDeCompra = $request->session()->get('ordenDeCompra', []);
        // dd($ordenDeCompra);
        // "codigo" => "6379808989577"
        // "detalle" => "Aluminio"
        // "rubro" => "Alimentación"
        // "proveedor" => "Vital"
        // "marca" => "Ford"
        // "cantidad" => "2"
        // "costo" => 340.35
        // "subTotal" => 680.7


            $total = 0;
    
            if (isset($ordenDeCompra['articulos'])) {

                foreach ($ordenDeCompra['articulos'] as $item) {
                    $total += $item['subTotal'];
                }
            }else{
                $ordenDeCompra['articulos'] = [];
            }

            if (!isset($ordenDeCompra['proveedor'])) {
                $ordenDeCompra['proveedor']['nombre'] = '';
                $ordenDeCompra['proveedor']['direccion'] = '';
                $ordenDeCompra['proveedor']['email'] = '';
                $ordenDeCompra['proveedor']['cuit'] = '';
                $ordenDeCompra['proveedor']['telefono'] = '';
    
            }else

            $proveedor['nombre'] = isset($ordenDeCompra['proveedor']['nombre']) ? $ordenDeCompra['proveedor']['nombre'] : ''; 
            $proveedor['cuit'] = isset($ordenDeCompra['proveedor']['cuit']) ? $ordenDeCompra['proveedor']['cuit'] : '';
            $proveedor['direccion'] = isset($ordenDeCompra['proveedor']['direccion']) ? $ordenDeCompra['proveedor']['direccion'] : '';
            $proveedor['email'] = isset($ordenDeCompra['proveedor']['email']) ? $ordenDeCompra['proveedor']['email'] : '';
            $proveedor['telefono'] = isset($ordenDeCompra['proveedor']['telefono']) ? $ordenDeCompra['proveedor']['telefono'] : '';



            $data = [
                'total' => $total,
                'proveedor' => 'Vital',
                'fecha' => Carbon::now()->format('d/m/Y'),
                'usuario' => Auth::user()->name,
                'empresa' => $empresa,

            ];

        $pdf = Pdf::loadView('PDF.pdfOrdenDeCompra',['ordenDeCompra'=>$ordenDeCompra,'data' => $data,'proveedor'=>$proveedor]);
        // $pdf->set_paper(array(0,0,250,(500)), 'portrait');
        // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

        $nombreArchivo= 'Orden de Compra: '.Carbon::now()->format('Ymd').'.pdf';
        // return $pdf->download($nombreArchivo);
        return $pdf->stream($nombreArchivo);   



    }
}
