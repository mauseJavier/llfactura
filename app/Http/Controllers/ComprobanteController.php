<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;


use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Comprobante;
use App\Models\productoComprobante;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Barryvdh\DomPDF\Facade\Pdf; 
use chillerlan\QRCode\{QRCode, QROptions}; 

class ComprobanteController extends Controller
{
    
    public function imprimir($comprobante_id , $formato = 'Ticket'){

  
        $empresa = Empresa::find(Auth::user()->empresa_id);  

        // dd($empresa);

        //    return Auth::user();

        $comprobante = Comprobante::where('id',$comprobante_id)->where('empresa_id',$empresa->id)->get();


        if ($comprobante->count() == 0) {
            return redirect()->route('factura')->with('mensaje','Nada para Imprimir');
    
        }

        $productos = productoComprobante::where('comprobante_id',$comprobante_id)->get();

        $totalRevisado = floatval(($comprobante[0]->total) > 0 ? $comprobante[0]->total : ($comprobante[0]->total * -1)); 
      
        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;

        // dd(count($productos));

        if(count($productos)== 0){
            $productos = null;
           
        }else{
            foreach ($productos as $key => $value) {     
                
                //     #attributes: array:8 [▼
                //     "id" => 9
                //     "comprobante_id" => 12
                //     "comprobante_numero" => 101
                //     "codigo" => "71697413"
                //     "detalle" => "laudantium"
                //     "precio" => 30.61
                //     "iva" => 10.5
                //     "cantidad" => 1.0
                // ]

                if($value->iva == 21){
                    
                    $importe_gravado_al21 += round($value->precio * $value->cantidad / 1.21,2);
                    $importe_iva_al21 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.21),2);

                    if($comprobante[0]->tipoComp == 1){
                        $productos[$key]->precio = round($value->precio / 1.21,2);
                    }
                    
                }elseif($value->iva == 10.5){
                    
                    $importe_gravado_al105 += round($value->precio * $value->cantidad / 1.105,2);
                    $importe_iva_al105 += round($value->precio * $value->cantidad -($value->precio * $value->cantidad / 1.105),2);

                    if($comprobante[0]->tipoComp == 1){
                        $productos[$key]->precio = round($value->precio / 1.105,2);
                    }
                }
            }

            
        }

        //para pruebas
        // $productos = null;

             
        if (Storage::disk('local')->exists( 'public/'.$empresa->cuit.'/logo/logo.png')) {
            // ...
            $url = Storage::url( 'public/'.$empresa->cuit.'/logo/logo.png');
            $path = Storage::path('public/'.$empresa->cuit.'/logo/logo.png');
            // $url = Storage::get('public/'.$empresa->cuit.'/logo/logo.png');
            // return  asset($url);
            // return $path;
            
        }else{
            $url = 'sin Imagen';
            $path ='sin Imagen';
        }



        if($comprobante[0]->tipoComp == 'remito' || 
            $comprobante[0]->tipoComp == 'presupuesto' ||
            $comprobante[0]->tipoComp == 'notaRemito') {
                
            $infoQR ='llfactura.com';

        }else{

            // dd(json_encode(
            //     array(                        
            //         "ver"=> 1,
            //         "fecha"=> Carbon::parse($comprobante[0]->fecha)->format('Y-m-d') ,
            //         "cuit"=> $empresa->cuit,
            //         "ptoVta"=> $comprobante[0]->ptoVta,
            //         "tipoCmp"=> $comprobante[0]->tipoComp,
            //         "nroCmp"=> $comprobante[0]->numero,
            //         "importe"=> floatval($comprobante[0]->total),
            //         "moneda"=> "PES",
            //         "ctz"=> 1,
            //         "tipoDocRec"=> $comprobante[0]->DocTipo,
            //         "nroDocRec"=>  $comprobante[0]->cuitCliente,
            //         "tipoCodAut"=> "E",
            //         "codAut"=> intval($comprobante[0]->cae))
            //     ));

            $infoQR ='https://www.afip.gob.ar/fe/qr/?p='. 
                    base64_encode(
                        json_encode(
                            array(                        
                                "ver"=> 1,
                                "fecha"=> Carbon::parse($comprobante[0]->fecha)->format('Y-m-d') ,
                                "cuit"=> $empresa->cuit,
                                "ptoVta"=> $comprobante[0]->ptoVta,
                                "tipoCmp"=> intval($comprobante[0]->tipoComp),
                                "nroCmp"=> $comprobante[0]->numero,
                                "importe"=> floatval($totalRevisado),
                                "moneda"=> "PES",
                                "ctz"=> 1,
                                "tipoDocRec"=> $comprobante[0]->DocTipo,
                                "nroDocRec"=>  $comprobante[0]->cuitCliente,
                                "tipoCodAut"=> "E",
                                "codAut"=> intval($comprobante[0]->cae))
                            )
                        );
        }
            

            
            // 'https://www.afip.gob.ar/fe/qr/?p=eyJ2ZXIiOjEsImZlY2hhIjoiMjAyMC0xMC0xMyIsImN1aXQiOjMwMDAwMDAwMDA3LCJwdG9WdGEiOjEwLCJ0aXBvQ21wIjoxLCJucm9DbXAiOjk0LCJpbXBvcnRlIjoxMjEwMCwibW9uZWRhIjoiRE9MIiwiY3R6Ijo2NSwidGlwb0RvY1JlYyI6ODAsIm5yb0RvY1JlYyI6MjAwMDAwMDAwMDEsInRpcG9Db2RBdXQiOiJFIiwiY29kQXV0Ijo3MDQxNzA1NDM2NzQ3Nn0=';
            $qrcode = (new QRCode)->render($infoQR);
            // default output is a base64 encoded data URI
            // printf('<img src="%s" alt="QR Code" />', $qrcode);

            // return $qrcode;

            // return view('PDF.pdfFacturaSin',['logo'=>asset($url),
            //                                     'qr'=>$qrcode,
            //                                         'titulo'=>'factura ejemplo'])->render();

            switch ($comprobante[0]->tipoContribuyente) {
                case 4:
                    $tipoContribuyenteCliente = 'Exento';
                    break;
                case 5:
                    $tipoContribuyenteCliente = 'Consumidor Final';
                    break;
                case 6:
                    $tipoContribuyenteCliente = 'Responsable Inscripto';
                    break;
                case 13:
                    $tipoContribuyenteCliente = 'Monotributista';
                    break;
            }

            switch ($comprobante[0]->tipoComp) {
                case 1:
                    $tipoComprobante = 'Factura';
                    $abreviatura = 'A';
                    $iva105 = $importe_iva_al105;
                    $iva21 =$importe_iva_al21;
                    $subtotal = $importe_gravado_al21 + $importe_gravado_al105;
                    break;
                case 6:
                    $tipoComprobante = 'Factura';
                    $abreviatura = 'B';

                    $iva105 = $importe_iva_al105;
                    $iva21 =$importe_iva_al21;
                    $subtotal = $importe_gravado_al21 + $importe_gravado_al105;
                    break;
                case 11:
                    $tipoComprobante = 'Factura';
                    $abreviatura = 'C';

                    $iva105 = 0;
                    $iva21 = 0;
                    $subtotal = 0;
                    break;
                case 'remito':
                    $tipoComprobante = 'Remito';
                    $abreviatura = 'R';

                    $iva105 = 0;
                    $iva21 = 0;
                    $subtotal = 0;
                    break;
                case 'presupuesto':
                    $tipoComprobante = 'Presupuesto';
                    $abreviatura = 'P';

                    $iva105 = 0;
                    $iva21 = 0;
                    $subtotal = 0;
                    break;
                case 3:
                    $tipoComprobante = 'NOTA CREDITO A';
                    $abreviatura = 'NCA';

                    $iva105 = $importe_iva_al105;
                    $iva21 =$importe_iva_al21;
                    $subtotal = $importe_gravado_al21 + $importe_gravado_al105;
                    break;
                case 8:
                    $tipoComprobante = 'NOTA CREDITO B';
                    $abreviatura = 'NCB';

                    $iva105 = 0;
                    $iva21 = 0;
                    $subtotal = 0;
                    break;
                case 13:
                    $tipoComprobante = 'NOTA CREDITO C';
                    $abreviatura = 'NCC';

                    $iva105 = 0;
                    $iva21 = 0;
                    $subtotal = 0;
                    break;
                case 'notaRemito':
                    $tipoComprobante = 'NOTA REMITO';
                    $abreviatura = 'NR';

                    $iva105 = 0;
                    $iva21 = 0;
                    $subtotal = 0;
                    break;
               
            }

            if($empresa->iva == 'ME'){
                $empresaIva = 'RESPONSABLE MONOTRIBUTO';

            }else{
                $empresaIva = 'RESPONSABLE INSCRIPTO';
            }

            $nombreFormaPago = FormaPago::find($comprobante[0]->idFormaPago);


            $info = [
                'logo'=>$path,
                'empresaNombre'=>$empresa->razonSocial,
                'numeroFactura'=> sprintf("%04d", $comprobante[0]->ptoVta)  .'-'. sprintf("%08d", $comprobante[0]->numero),
                'cuitEmpresa'=>$empresa->cuit,
                'empresaIva'=>$empresaIva,

                'inicioActividades'=> date('d-m-Y', strtotime($empresa->inicioActividades)) ,
                'fechaFactura'=>date('d-m-Y', strtotime($comprobante[0]->fecha)),

                'direccionEmpresa'=>Auth::user()->domicilio,

                'telefonoEmpresa'=>$empresa->telefono,
                'titularEmpresa'=>$empresa->titular,
                'tipoFactura'=>$tipoComprobante,
                'abreviatura'=>$abreviatura,
                
                'codigoFactura'=>$comprobante[0]->tipoComp,
                'nombreCliente'=>$comprobante[0]->razonSocial,
                'cuitCliente'=>$comprobante[0]->cuitCliente,
                'domicilioCliente'=>$comprobante[0]->domicilio,
                'tipoContribuyente'=>$tipoContribuyenteCliente,
                'leyenda'=>$comprobante[0]->leyenda,
                'nombreFormaPago'=>$nombreFormaPago->nombre,
                'producto'=> $productos,
                'subtotal'=> $subtotal,
                'iva105'=> $iva105 ,
                'iva21'=> $iva21 ,
                'totalVenta'=> number_format($totalRevisado, 2) ,
                'cae'=>$comprobante[0]->cae,
                'vtocae'=>date('d-m-Y', strtotime($comprobante[0]->fechaVencimiento)),
                'qr'=>$qrcode,
                'titulo'=>'Factura '.$tipoComprobante .' N '.$comprobante[0]->numero.' '. date('dmY', strtotime($comprobante[0]->fecha)).' ' .$comprobante[0]->razonSocial
            ];


            // return $info;

            if($formato == 'Ticket'){
                //tamaño custom, se especifica en puntos, lo que en CSS se escribe como pt
                
                $pdf = Pdf::loadView('PDF.pdfFacturaTicket',$info);
                $pdf->set_paper(array(0,0,250,(550 + (count($productos) * 25))), 'portrait');
            }else{

                $pdf = Pdf::loadView('PDF.pdfFactura',$info);
            }

            

            // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));

            $nombreArchivo= 'Factura '.$tipoComprobante .' N '.$comprobante[0]->numero.' '. date('dmY', strtotime($comprobante[0]->fecha)).' ' .$comprobante[0]->razonSocial.'.pdf';
            // return $pdf->download($nombreArchivo);
            return $pdf->stream($nombreArchivo);          
           



    }
}
