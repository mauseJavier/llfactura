<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Presupuesto;
use App\Models\ProductoPresupuesto;
use App\Models\Comprobante;
use App\Models\productoComprobante;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use Barryvdh\DomPDF\Facade\Pdf; 
use chillerlan\QRCode\{QRCode, QROptions}; 

class PdfComprobanteGenerar
{
    public function imprimir($comprobante_id , $formato = 'Ticket'){

        $empresa = Empresa::find(Comprobante::find($comprobante_id)->empresa_id);

        $comprobante = Comprobante::where('id',$comprobante_id)->where('empresa_id',$empresa->id)->get();

        if ($comprobante->count() == 0) {
            return redirect()->route('factura')->with('mensaje','Nada para Imprimir');
        }

        $productos = productoComprobante::where('comprobante_id',$comprobante_id)->get();

        $info = $this->generarInfoPdf($empresa, $comprobante, $productos, $formato , Auth::user());

        if($formato == 'Ticket'){
            $pdf = Pdf::loadView('PDF.pdfFacturaTicket',$info);
            $pdf->set_paper(array(0,0,250,(600 + (count($productos) * 47.5))), 'portrait');
        }else{
            $pdf = Pdf::loadView('PDF.pdfFactura',$info);
        }

        $nombreArchivo= 'Factura '.$info['tipoFactura'] .' N '.$info['numeroFactura'].' '. $info['fechaFactura'] .' ' .$info['nombreCliente'].'.pdf';
        
        return $pdf->stream($nombreArchivo);          
    }

    public function obtenerPdfBase64($comprobante_id, $formato = 'Ticket', $usuarioId) {

        $usuario = User::find($usuarioId);

        $empresa = Empresa::find(Comprobante::find($comprobante_id)->empresa_id);

        $comprobante = Comprobante::where('id', $comprobante_id)->where('empresa_id', $empresa->id)->get();

        if ($comprobante->count() == 0) {
            return response()->json(['error' => 'Nada para generar'], 404);
        }

        $productos = productoComprobante::where('comprobante_id', $comprobante_id)->get();

        $info = $this->generarInfoPdf($empresa, $comprobante, $productos, $formato , $usuario);

        if ($formato == 'Ticket') {
            $pdf = Pdf::loadView('PDF.pdfFacturaTicket', $info);
            $pdf->set_paper([0, 0, 250, (600 + (count($productos) * 47.5))], 'portrait');
        } else {
            $pdf = Pdf::loadView('PDF.pdfFactura', $info);
        }

        $pdfContent = $pdf->output();
        $base64Pdf = base64_encode($pdfContent);

        return $base64Pdf;
    }

    private function generarInfoPdf($empresa, $comprobante, $productos, $formato, $usuario = null) 
    {
        $totalRevisado = floatval(($comprobante[0]->total) > 0 ? $comprobante[0]->total : ($comprobante[0]->total * -1)); 
      
        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;
        $totalDescuento=0;
        $subTotalPrecioLista=0;

        if(count($productos)== 0){
            $productos = null;
        }else{
            foreach ($productos as $key => $value) {     

                if($value->iva == 21){
                    
                    $importe_gravado_al21 += round($value->precio * $value->cantidad / 1.21,3);
                    $importe_iva_al21 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.21),3);

                    if($comprobante[0]->tipoComp == 1 OR $comprobante[0]->tipoComp == 51){

                        $productos[$key]->precio = round($value->precio / 1.21,3);

                        $productos[$key]->precioLista = round($value->precioLista / 1.21,3);
                        $productos[$key]->descuento = round($value->descuento / 1.21,3);

                    }
                    
                }elseif($value->iva == 10.5){
                    
                    $importe_gravado_al105 += round($value->precio * $value->cantidad / 1.105,3);
                    $importe_iva_al105 += round($value->precio * $value->cantidad -($value->precio * $value->cantidad / 1.105),3);

                    if($comprobante[0]->tipoComp == 1 OR $comprobante[0]->tipoComp == 51){

                        $productos[$key]->precio = round($value->precio / 1.105,3);

                        $productos[$key]->precioLista = round($value->precioLista / 1.105,3);
                        $productos[$key]->descuento = round($value->descuento / 1.105,3);

                    }
                }

                $totalDescuento += round($productos[$key]->descuento * $value->cantidad,3);
                $subTotalPrecioLista += round(($productos[$key]->precioLista * $value->cantidad),3);

            }
        }

            $logoPaths = [
                'public/'.$empresa->cuit.'/' . $empresa->razonSocial . '/logo/logo.png',
                'public/'.$empresa->cuit.'/logo/logo.png',
            ];

            $url = 'sin Imagen';
            $path = 'sin Imagen';

            foreach ($logoPaths as $logoPath) {
                if (Storage::disk('local')->exists($logoPath)) {
                    $url = Storage::url($logoPath);
                    $path = Storage::path($logoPath);
                    break;
                }
            }


        if (Storage::disk('local')->exists( 'public/'.$empresa->cuit.'/logo/logoAgua.png')) {
            $urlAgua = Storage::url( 'public/'.$empresa->cuit.'/logo/logoAgua.png');
            $logoAgua = Storage::path('public/'.$empresa->cuit.'/logo/logoAgua.png');
        }else{
            $urlAgua = '';
            $logoAgua ='';
        }

        if($comprobante[0]->tipoComp == 'remito' || 
            $comprobante[0]->tipoComp == 'presupuesto' ||
            $comprobante[0]->tipoComp == 'notaRemito') {
                
            $infoQR ='llfactura.com';

        }else{
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
            
        $qrcode = (new QRCode)->render($infoQR);

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
            case 51:
                $tipoComprobante = 'Factura';
                $abreviatura = 'M';
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
            case 53:
                $tipoComprobante = 'NOTA CREDITO M';
                $abreviatura = 'NCM';

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
        $nombreFormaPago2 = FormaPago::find($comprobante[0]->idFormaPago2);

        $info = [
            'logo'=>$path,
            'logoAgua'=>$logoAgua,
            'empresaNombre'=>$empresa->razonSocial,
            'numeroFactura'=> sprintf("%04d", $comprobante[0]->ptoVta)  .'-'. sprintf("%08d", $comprobante[0]->numero),
            'cuitEmpresa'=>$empresa->cuit,
            'ingresosBrutos'=>$empresa->ingresosBrutos,
            'empresaIva'=>$empresaIva,
            'empresaCorreo'=>$empresa->correo,
            'inicioActividades'=> date('d-m-Y', strtotime($empresa->inicioActividades)) ,
            'fechaFactura'=>date('d-m-Y', strtotime($comprobante[0]->fecha)),
            'direccionEmpresa'=> $usuario ? $usuario->domicilio : Auth::user()->domicilio,
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
            'nombreFormaPago2'=>$nombreFormaPago2->nombre,
            'producto'=> $productos,
            'subtotal'=> number_format($subtotal,2),
            'subTotalPrecioLista'=>number_format($subTotalPrecioLista, 2) ,
            'iva105'=> number_format($iva105,2) ,
            'iva21'=> number_format($iva21 ,2),
            'totalVenta'=> number_format($totalRevisado, 2) ,
            'totalDescuento'=> number_format($totalDescuento, 2) ,
            'cae'=>$comprobante[0]->cae,
            'vtocae'=>date('d-m-Y', strtotime($comprobante[0]->fechaVencimiento)),
            'qr'=>$qrcode,
            'titulo'=>'Factura '.$tipoComprobante .' N '.$comprobante[0]->numero.' '. date('dmY', strtotime($comprobante[0]->fecha)).' ' .$comprobante[0]->razonSocial
        ];

        return $info;
    }

    private function generarDatosPresupuesto($presupuesto_id, $usuario = null) {

        
        $presupuesto = Presupuesto::where('id', $presupuesto_id)->get();
        
        $empresa = Empresa::find($presupuesto[0]->empresa_id);


        if ($presupuesto->count() == 0) {
            return null;
        }

        $productos = ProductoPresupuesto::where('presupuesto_id', $presupuesto_id)->get();

        $totalRevisado = floatval(($presupuesto[0]->total) > 0 ? $presupuesto[0]->total : ($presupuesto[0]->total * -1));

        $totalDescuento = 0;
        $subTotalPrecioLista = 0;

        foreach ($productos as $key => $value) {
            $totalDescuento += round($value->descuento * $value->cantidad, 3);
            $subTotalPrecioLista += round($value->precioLista * $value->cantidad, 3);
        }

        $logoPaths = [
            'public/'.$empresa->cuit.'/' . $empresa->razonSocial . '/logo/logo.png',
            'public/'.$empresa->cuit.'/logo/logo.png',
        ];

        $url = 'sin Imagen';
        $path = 'sin Imagen';

        foreach ($logoPaths as $logoPath) {
            if (Storage::disk('local')->exists($logoPath)) {
                $url = Storage::url($logoPath);
                $path = Storage::path($logoPath);
                break;
            }
        }

        $infoQR = 'llfactura.com';
        $qrcode = (new QRCode)->render($infoQR);

        switch ($presupuesto[0]->tipoContribuyente) {
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

        $tipoComprobante = 'PRESUPUESTO';
        $nombreFormaPago = FormaPago::find($presupuesto[0]->idFormaPago);

        return [
            'empresa' => $empresa,
            'presupuesto' => $presupuesto[0],
            'productos' => $productos,
            'totalRevisado' => $totalRevisado,
            'totalDescuento' => $totalDescuento,
            'subTotalPrecioLista' => $subTotalPrecioLista,
            'path' => $path,
            'qrcode' => $qrcode,
            'tipoContribuyenteCliente' => $tipoContribuyenteCliente,
            'tipoComprobante' => $tipoComprobante,
            'nombreFormaPago' => $nombreFormaPago
        ];
    }

    public function imprimirPresupuesto($presupuesto_id, $formato = 'A4') {
        $datos = $this->generarDatosPresupuesto($presupuesto_id, Auth::user());

        if (!$datos) {
            return redirect()->route('factura')->with('mensaje', 'Nada para Imprimir');
        }

        $info = [
            'logo' => $datos['path'],
            'empresaNombre' => $datos['empresa']->razonSocial,
            'numeroPresupuesto' => $datos['presupuesto']->numero,
            'cuitEmpresa' => $datos['empresa']->cuit,
            'inicioActividades' => date('d-m-Y', strtotime($datos['empresa']->inicioActividades)),
            'fechaPresupuesto' => date('d-m-Y', strtotime($datos['presupuesto']->fecha)),
            'fechaVencimiento' => date('d-m-Y', strtotime($datos['presupuesto']->fechaVencimiento)),
            'direccionEmpresa' => $datos['empresa']->domicilio,
            'telefonoEmpresa' => $datos['empresa']->telefono,
            'titularEmpresa' => $datos['empresa']->titular,
            'tipoFactura' => $datos['tipoComprobante'],
            'nombreCliente' => $datos['presupuesto']->razonSocial,
            'cuitCliente' => $datos['presupuesto']->cuitCliente,
            'domicilioCliente' => $datos['presupuesto']->domicilio,
            'tipoContribuyente' => $datos['tipoContribuyenteCliente'],
            'leyenda' => $datos['presupuesto']->leyenda,
            'nombreFormaPago' => $datos['nombreFormaPago']->nombre,
            'producto' => $datos['productos'],
            'subTotalPrecioLista' => $datos['subTotalPrecioLista'],
            'totalDescuento' => number_format($datos['totalDescuento'], 2),
            'totalVenta' => number_format($datos['totalRevisado'], 2),
            'qr' => $datos['qrcode'],
            'titulo' => $datos['tipoComprobante'] . ' N ' . $datos['presupuesto']->numero . ' ' . date('dmY', strtotime($datos['presupuesto']->fecha)) . ' ' . $datos['presupuesto']->razonSocial
        ];

        if ($formato == 'Ticket') {
            $pdf = Pdf::loadView('PDF.pdfPresupuestoTicket', $info);
            $pdf->set_paper([0, 0, 250, (550 + (count($datos['productos']) * 25))], 'portrait');
        } else {
            $pdf = Pdf::loadView('PDF.pdfPresupuesto', $info);
        }

        return $pdf->stream();
    }

    public function obtenerPdfBase64Presupuesto($presupuesto_id, $formato = 'A4', $usuarioId) {
        $usuario = User::find($usuarioId);

        $datos = $this->generarDatosPresupuesto($presupuesto_id, $usuario);

        if (!$datos) {
            return response()->json(['error' => 'Nada para generar'], 404);
        }

        $info = [
            'logo' => $datos['path'],
            'empresaNombre' => $datos['empresa']->razonSocial,
            'numeroPresupuesto' => $datos['presupuesto']->numero,
            'cuitEmpresa' => $datos['empresa']->cuit,
            'inicioActividades' => date('d-m-Y', strtotime($datos['empresa']->inicioActividades)),
            'fechaPresupuesto' => date('d-m-Y', strtotime($datos['presupuesto']->fecha)),
            'fechaVencimiento' => date('d-m-Y', strtotime($datos['presupuesto']->fechaVencimiento)),
            'direccionEmpresa' => $datos['empresa']->domicilio,
            'telefonoEmpresa' => $datos['empresa']->telefono,
            'titularEmpresa' => $datos['empresa']->titular,
            'tipoFactura' => $datos['tipoComprobante'],
            'nombreCliente' => $datos['presupuesto']->razonSocial,
            'cuitCliente' => $datos['presupuesto']->cuitCliente,
            'domicilioCliente' => $datos['presupuesto']->domicilio,
            'tipoContribuyente' => $datos['tipoContribuyenteCliente'],
            'leyenda' => $datos['presupuesto']->leyenda,
            'nombreFormaPago' => $datos['nombreFormaPago']->nombre,
            'producto' => $datos['productos'],
            'subTotalPrecioLista' => $datos['subTotalPrecioLista'],
            'totalDescuento' => number_format($datos['totalDescuento'], 2),
            'totalVenta' => number_format($datos['totalRevisado'], 2),
            'qr' => $datos['qrcode'],
            'titulo' => $datos['tipoComprobante'] . ' N ' . $datos['presupuesto']->numero . ' ' . date('dmY', strtotime($datos['presupuesto']->fecha)) . ' ' . $datos['presupuesto']->razonSocial
        ];

        if ($formato == 'Ticket') {
            $pdf = Pdf::loadView('PDF.pdfPresupuestoTicket', $info);
            $pdf->set_paper([0, 0, 250, (550 + (count($datos['productos']) * 25))], 'portrait');
        } else {
            $pdf = Pdf::loadView('PDF.pdfPresupuesto', $info);
        }

        $pdfContent = $pdf->output();
        return base64_encode($pdfContent);
    }
}
