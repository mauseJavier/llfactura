<?php

namespace App\Livewire\Comprobante;

use Livewire\Component;


use Afip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;



use App\Models\Empresa;
use App\Models\Comprobante;
use App\Models\Deposito;
use App\Models\FormaPago;
use App\Models\productoComprobante;


class FacturarRemito extends Component
{

    
    public $comp;


    public $tipoComprobante;

    public $imprimir;

    public $fechaHoy;
    public $empresa;
    public $usuario;


    public function mount(Comprobante $idComprobante) 
    {
        // $this->post = Post::findOrFail($id);

        $this->comp = $idComprobante;

        $this->fechaHoy = date('Y-m-d');

        //datos de empresa
        // $titles = DB::table('users')->pluck('title');
        $this->empresa = Empresa::find(Auth::user()->empresa_id);
        $this->usuario = Auth::user();

        if( $this->empresa->iva == 'ME' AND $this->empresa->fe == 'si'){
            $this->tipoComprobante = 11;
        }elseif($this->empresa->iva == 'RI' AND $this->empresa->fe == 'si'){
            $this->tipoComprobante = 6;
        }else{
            $this->tipoComprobante = 11;
        }
        $this->imprimir = $this->empresa->imprimirSiNo;

    }


    public function render()
    {
        return view('livewire.comprobante.facturar-remito',
        [
            // 'productos'=> productoComprobante::Where('comprobante_id',$this->comp->id)->paginate(30),
            'depo'=> Deposito::find($this->comp->deposito_id),

            'fpUno'=> FormaPago::select('nombre')->find($this->comp->idFormaPago),
            'fpDos'=> FormaPago::select('nombre')->find($this->comp->idFormaPago2),



        ])
        ->extends('layouts.app')
        ->section('main');
    }



    public function facturar()
    {




        // AK TENEMOS QUE SABER SI VAMOS A HACER A B C O REMITO PRESUPUESTO    
        if($this->tipoComprobante == 11){
            $descripcionTipoComp = 'Factura C';
            $nuevoComprobante = $this->crearComprobanteC();
            $comprobanteId = $this->ActualizarComprobante($nuevoComprobante,$descripcionTipoComp);            
        }elseif($this->tipoComprobante == 6){
            $descripcionTipoComp = 'Factura B';
            $nuevoComprobante = $this->crearComprobanteB();
            $comprobanteId = $this->ActualizarComprobante($nuevoComprobante,$descripcionTipoComp);
        }elseif($this->tipoComprobante == 1){
            $descripcionTipoComp = 'Factura A';
            $nuevoComprobante = $this->crearComprobanteA();
            $comprobanteId = $this->ActualizarComprobante($nuevoComprobante,$descripcionTipoComp);

        }elseif($this->tipoComprobante == 51){
            $descripcionTipoComp = 'Factura M';
            $nuevoComprobante = $this->crearComprobanteM();
            $comprobanteId = $this->ActualizarComprobante($nuevoComprobante,$descripcionTipoComp);

        }
        
        else{
            dd('Tipo de comprobante erroneo: '.$this->tipoComprobante);
        }

        // dd($nuevoComprobante);



        if($this->imprimir){

            if ($this->tipoComprobante == 'presupuesto'){

                $this->redirectRoute('formatoPDF',['comprobante_id'=>$comprobanteId,
                                                    'tipo'=>'presupuesto']);
            }else{

                $this->redirectRoute('formatoPDF',['comprobante_id'=>$comprobanteId,
                                                    'tipo'=>'factura']);
               
            }


        }else{

            $this->redirect('/nuevoComprobante'); 

        }



                           
    }


    function objetoAfip(){
        // // Certificado (Puede estar guardado en archivos, DB, etc)
        // $cert = file_get_contents('./certificado.crt');

        // // Key (Puede estar guardado en archivos, DB, etc)
        // $key = file_get_contents('./key.key');

        // dd(Storage::disk('local')->exists('public/'.$this->empresa->cuit.'/ert.crt') );


        if (Storage::disk('local')->exists('public/'.$this->empresa->cuit.'/cert.crt') ) {
            // ...
            $cert = Storage::get('public/'.$this->empresa->cuit.'/cert.crt');

            // return response()->json($cert, 200);
            
        }else
        {
            dd('No existe certificado');
        }

        if ( Storage::disk('local')->exists('public/'.$this->empresa->cuit.'/key.key')) {
            // ...

            $key = Storage::get('public/'.$this->empresa->cuit.'/key.key');

            // return response()->json($key, 200);
            
        }else
        {
            dd('No existe key');
        }



        $afip = new Afip(array(
            'CUIT' => $this->empresa->cuit,
            'cert' => $cert,
            'key' => $key,
            'access_token' => env('tokenAFIPsdk'),
            'production' => TRUE
        ));

        return $afip;
    }

    function crearComprobanteC(){


        if($this->empresa->fe == 'si'){

        $afip = $this->objetoAfip();

        }else{
        //cuit de pruebas 
        $afip = new Afip(array('CUIT' => 20409378472));
        }



            /**
             * Numero del punto de venta
             **/
            $punto_de_venta = $this->usuario->puntoVenta;

            /**
             * Tipo de factura
             **/
            $tipo_de_comprobante = 11; // 11 = Factura C

            /**
             * Número de la ultima Factura C
             **/
            $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_comprobante);

            /**
             * Concepto de la factura
             *
             * Opciones:
             *
             * 1 = Productos 
             * 2 = Servicios 
             * 3 = Productos y Servicios
             **/
            $concepto = 1;

            /**
             * Tipo de documento del comprador
             *
             * Opciones:
             *
             * 80 = CUIT 
             * 86 = CUIL 
             * 96 = DNI
             * 99 = Consumidor Final 
             **/
            $tipo_de_documento = $this->comp->DocTipo;

            /**
             * Numero de documento del comprador (0 para consumidor final)
             **/
            $numero_de_documento = $this->comp->cuitCliente;

            /**
             * Numero de comprobante
             **/
            $numero_de_factura = $last_voucher+1;

            /**
             * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
             **/
            $fecha = $this->fechaHoy;

            /**
             * Importe de la Factura
             **/
            $importe_total = $this->comp->total;



            /**
             * Los siguientes campos solo son obligatorios para los conceptos 2 y 3
             **/
            if ($concepto === 2 || $concepto === 3) {
                /**
                 * Fecha de inicio de servicio en formato aaaammdd
                 **/
                $fecha_servicio_desde = intval(date('Ymd'));

                /**
                 * Fecha de fin de servicio en formato aaaammdd
                 **/
                $fecha_servicio_hasta = intval(date('Ymd'));

                /**
                 * Fecha de vencimiento del pago en formato aaaammdd
                 **/
                $fecha_vencimiento_pago = intval(date('Ymd'));
            }
            else {
                $fecha_servicio_desde = null;
                $fecha_servicio_hasta = null;
                $fecha_vencimiento_pago = null;
            }


            $data = array(
                'CantReg' 	=> 1, // Cantidad de facturas a registrar
                'PtoVta' 	=> $punto_de_venta,
                'CbteTipo' 	=> $tipo_de_comprobante, 
                'Concepto' 	=> $concepto,
                'DocTipo' 	=> $tipo_de_documento,
                'DocNro' 	=> $numero_de_documento,
                'CbteDesde' => $numero_de_factura,
                'CbteHasta' => $numero_de_factura,
                'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
                'FchServDesde'  => $fecha_servicio_desde,
                'FchServHasta'  => $fecha_servicio_hasta,
                'FchVtoPago'    => $fecha_vencimiento_pago,
                'ImpTotal' 	=> round($importe_total,2) ,
                'ImpTotConc'=> 0, // Importe neto no gravado
                'ImpNeto' 	=> round($importe_total,2), // Importe neto
                'ImpOpEx' 	=> 0, // Importe exento al IVA
                'ImpIVA' 	=> 0, // Importe de IVA
                'ImpTrib' 	=> 0, //Importe total de tributos
                'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
                'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            );

            // dd($data);

            /** 
             * Creamos la Factura 
             **/
            $return_full_response=TRUE;

            // $res = $afip->ElectronicBilling->CreateVoucher($data);
            $res = $afip->ElectronicBilling->CreateVoucher($data, $return_full_response);

            return $res;

            // /**
            //  * Mostramos por pantalla los datos de la nueva Factura 
            //  **/
            // // var_dump(array(
            // //     'cae' => $res['CAE'], //CAE asignado a la Factura
            // //     'vencimiento' => $res['CAEFchVto'] //Fecha de vencimiento del CAE
            // // ));


            // // Descargamos el HTML de ejemplo (ver mas arriba)
            // // y lo guardamos como bill.html
            // // $html = file_get_contents('./bill.html');
            // if (Storage::disk('local')->exists('public/pdf/factura.html') ) {
            //     // ...
            //     $factura = Storage::get('public/pdf/factura.html');

            //     // return response()->json($cert, 200);
                
            // }else
            // {
            //     return response()->json('no existe factura', 200);
            // }

            // // Nombre para el archivo (sin .pdf)
            // $name = 'PDF de prueba';
                
            // // Opciones para el archivo
            // $options = array(
            //     "width" => 8, // Ancho de pagina en pulgadas. Usar 3.1 para ticket
            //     "marginLeft" => 0.4, // Margen izquierdo en pulgadas. Usar 0.1 para ticket 
            //     "marginRight" => 0.4, // Margen derecho en pulgadas. Usar 0.1 para ticket 
            //     "marginTop" => 0.4, // Margen superior en pulgadas. Usar 0.1 para ticket 
            //     "marginBottom" => 0.4 // Margen inferior en pulgadas. Usar 0.1 para ticket 
            // );

            // // Creamos el PDF
            // $resPDF = $afip->ElectronicBilling->CreatePDF(array(
            //     "html" => $factura,
            //     "file_name" => $name,
            //     "options" => $options
            // ));

            // // Mostramos la url del archivo creado
            // // var_dump($res['file']);
            

            // return array(['resultadoAFIP'=>$res,
            //               'resPdf'=>$resPDF['file'],
            //             ]);

    }

    function crearComprobanteB(){

        if($this->empresa->fe == 'si'){

        $afip = $this->objetoAfip();

        }else{
        //cuit de pruebas 
        $afip = new Afip(array('CUIT' => 20409378472));
        }
        /**
         * Numero del punto de venta
         **/
        $punto_de_venta = $this->usuario->puntoVenta;

        /**
         * Tipo de factura
         **/
        $tipo_de_factura = 6; // 6 = Factura B

        /**
         * Número de la ultima Factura B
         **/

        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_factura);

        /**
         * Concepto de la factura
         *
         * Opciones:
         *
         * 1 = Productos 
         * 2 = Servicios 
         * 3 = Productos y Servicios
         **/
        $concepto = 1;

        /**
         * Tipo de documento del comprador
         *
         * Opciones:
         *
         * 80 = CUIT 
         * 86 = CUIL 
         * 96 = DNI
         * 99 = Consumidor Final 
         **/
        $tipo_de_documento = $this->comp->DocTipo;

        /**
         * Numero de documento del comprador (0 para consumidor final)
         **/
        $numero_de_documento = $this->comp->cuitCliente;

        /**
         * Numero de factura
         **/
        $numero_de_factura = $last_voucher+1;

        /**
         * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
         **/
        $fecha = $this->fechaHoy; // date('Y-m-d');

        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;

        $articulos = productoComprobante::Where('comprobante_id',$this->comp->id)->get();


        foreach ( $articulos as $key => $value) {

            
            if($value->iva == 21){

                $importe_gravado_al21 += round($value->precio * $value->cantidad / 1.21,2);
                $importe_iva_al21 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.21),2);

            }elseif($value->iva == 10.5){


                $importe_gravado_al105 += round($value->precio * $value->cantidad / 1.105,2);
                $importe_iva_al105 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.105),2);
            }

            // dump($importe_gravado);
            // dump($importe_iva);
        }


            /** 
         * Importe sujeto al IVA (sin icluir IVA)
         **/
        $importe_gravado = round($importe_gravado_al21 + $importe_gravado_al105,2); //  dato ejemplo 100;

        /**
         * Importe exento al IVA
         **/
        $importe_exento_iva = 0;

        /**
         * Importe de IVA
         **/
        $importe_iva = round($importe_iva_al21 + $importe_iva_al105,2); //  dato ejemplo 21;

        if($importe_gravado_al21 > 0){
            $arrayIva[] =// Alícuotas asociadas al factura
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 = 21%)
                    'BaseImp' 	=> round($importe_gravado_al21,2),
                    'Importe' 	=> round($importe_iva_al21,2) 
                
                
                );

        }

        if($importe_gravado_al105 > 0){
            $arrayIva[] = // Alícuotas asociadas al factura
                
                array(
                    'Id' 		=> 4, // Id del tipo de IVA (4 = 105%)
                    'BaseImp' 	=> round($importe_gravado_al105,2),
                    'Importe' 	=> round($importe_iva_al105,2)
                
                );

        }


        // dd($arrayIva);

        /**
         * Los siguientes campos solo son obligatorios para los conceptos 2 y 3
         **/
        if ($concepto === 2 || $concepto === 3) {
            /**
             * Fecha de inicio de servicio en formato aaaammdd
             **/
            $fecha_servicio_desde = intval(date('Ymd'));

            /**
             * Fecha de fin de servicio en formato aaaammdd
             **/
            $fecha_servicio_hasta = intval(date('Ymd'));

            /**
             * Fecha de vencimiento del pago en formato aaaammdd
             **/
            $fecha_vencimiento_pago = intval(date('Ymd'));
        }
        else {
            $fecha_servicio_desde = null;
            $fecha_servicio_hasta = null;
            $fecha_vencimiento_pago = null;
        }

        // 1. Alícuotas de IVA
        // CÓDIGO DESCRIPCIÓN
        // 0003 0,00 %
        // 0004 10,50 %
        // 0005 21,00 %
        // 0006 27,00 %
        // 0008 5,00 %
        // 0009 2,50 %

        $data = array(
            'CantReg' 	=> 1, // Cantidad de facturas a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_factura, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_factura,
            'CbteHasta' => $numero_de_factura,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => $fecha_vencimiento_pago,
            'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva,2),
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_gravado,
            'ImpOpEx' 	=> $importe_exento_iva,
            'ImpIVA' 	=> $importe_iva,
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> $arrayIva, 
        );

        // dd($data);
        /** 
         * Creamos la Factura 
         **/
        $res = $afip->ElectronicBilling->CreateVoucher($data,TRUE);


        /**
         * Mostramos por pantalla los datos de la nueva Factura 
         **/
        // dd(array(
        //     'cae' => $res['CAE'], //CAE asignado a la Factura
        //     'vencimiento' => $res['CAEFchVto'], //Fecha de vencimiento del CAE
        //     'res'=>$res,
        // ));

        return $res;
    }

    function crearComprobanteA(){

        if($this->empresa->fe == 'si'){

        $afip = $this->objetoAfip();

        }else{
        //cuit de pruebas 
        $afip = new Afip(array('CUIT' => 20409378472));
        }
        /**
         * Numero del punto de venta
         **/
        $punto_de_venta = $this->usuario->puntoVenta;

        /**
         * Tipo de factura
         **/
        $tipo_de_factura = 1; // 1 = Factura A

        /**
         * Número de la ultima Factura A
         **/
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_factura);

        /**
         * Concepto de la factura
         *
         * Opciones:
         *
         * 1 = Productos 
         * 2 = Servicios 
         * 3 = Productos y Servicios
         **/
        $concepto = 1;

        /**
         * Tipo de documento del comprador
         *
         * Opciones:
         *
         * 80 = CUIT 
         * 86 = CUIL 
         * 96 = DNI
         * 99 = Consumidor Final 
         **/
        $tipo_de_documento = $this->comp->DocTipo;

        /**
         * Numero de documento del comprador (0 para consumidor final)
         **/
        $numero_de_documento = $this->comp->cuitCliente;

        /**
         * Numero de factura
         **/
        $numero_de_factura = $last_voucher+1;

        /**
         * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
         **/
        $fecha = $this->fechaHoy; // date('Y-m-d');

        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;


        $articulos = productoComprobante::Where('comprobante_id',$this->comp->id)->get();


        foreach ( $articulos as $key => $value) {

            
            if($value->iva == 21){

                $importe_gravado_al21 += round($value->precio * $value->cantidad / 1.21,2);
                $importe_iva_al21 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.21),2);

            }elseif($value->iva == 10.5){


                $importe_gravado_al105 += round($value->precio * $value->cantidad / 1.105,2);
                $importe_iva_al105 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.105),2);
            }

            // dump($importe_gravado);
            // dump($importe_iva);
        }

            /** 
         * Importe sujeto al IVA (sin icluir IVA)
         **/
        $importe_gravado = round($importe_gravado_al21 + $importe_gravado_al105,2); //  dato ejemplo 100;

        /**
         * Importe exento al IVA
         **/
        $importe_exento_iva = 0;

        /**
         * Importe de IVA
         **/
        $importe_iva = round($importe_iva_al21 + $importe_iva_al105,2); //  dato ejemplo 21;

        if($importe_gravado_al21 > 0){
            $arrayIva[] =// Alícuotas asociadas al factura
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 = 21%)
                    'BaseImp' 	=> round($importe_gravado_al21,2),
                    'Importe' 	=> round($importe_iva_al21,2) 
                
                
                );

        }

        if($importe_gravado_al105 > 0){
            $arrayIva[] = // Alícuotas asociadas al factura
                
                array(
                    'Id' 		=> 4, // Id del tipo de IVA (4 = 105%)
                    'BaseImp' 	=> round($importe_gravado_al105,2),
                    'Importe' 	=> round($importe_iva_al105,2)
                
                );

        }


        // dd($arrayIva);

        /**
         * Los siguientes campos solo son obligatorios para los conceptos 2 y 3
         **/
        if ($concepto === 2 || $concepto === 3) {
            /**
             * Fecha de inicio de servicio en formato aaaammdd
             **/
            $fecha_servicio_desde = intval(date('Ymd'));

            /**
             * Fecha de fin de servicio en formato aaaammdd
             **/
            $fecha_servicio_hasta = intval(date('Ymd'));

            /**
             * Fecha de vencimiento del pago en formato aaaammdd
             **/
            $fecha_vencimiento_pago = intval(date('Ymd'));
        }
        else {
            $fecha_servicio_desde = null;
            $fecha_servicio_hasta = null;
            $fecha_vencimiento_pago = null;
        }

        // 1. Alícuotas de IVA
        // CÓDIGO DESCRIPCIÓN
        // 0003 0,00 %
        // 0004 10,50 %
        // 0005 21,00 %
        // 0006 27,00 %
        // 0008 5,00 %
        // 0009 2,50 %

        $data = array(
            'CantReg' 	=> 1, // Cantidad de facturas a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_factura, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_factura,
            'CbteHasta' => $numero_de_factura,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => $fecha_vencimiento_pago,
            'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva,2),
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_gravado,
            'ImpOpEx' 	=> $importe_exento_iva,
            'ImpIVA' 	=> $importe_iva,
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> $arrayIva, 
        );
        // dd($data);

        /** 
         * Creamos la Factura 
         **/
        $res = $afip->ElectronicBilling->CreateVoucher($data,TRUE);

        /**
         * Mostramos por pantalla los datos de la nueva Factura 
         **/
        // dd(array(
        //     'cae' => $res['CAE'], //CAE asignado a la Factura
        //     'vencimiento' => $res['CAEFchVto'], //Fecha de vencimiento del CAE
        //     'res'=>$res,
        // ));

        return $res;
    }

    //FACTURAS M CODIGO 51 NOTA DE CREDITO M 53
    function crearComprobanteM(){

        if($this->empresa->fe == 'si'){

        $afip = $this->objetoAfip();

        }else{
        //cuit de pruebas 
        $afip = new Afip(array('CUIT' => 20409378472));
        }
        /**
         * Numero del punto de venta
         **/
        $punto_de_venta = $this->usuario->puntoVenta;

        /**
         * Tipo de factura
         **/
        $tipo_de_factura = 51; // 51 = Factura M, 1 = Factura A

        /**
         * Número de la ultima Factura A
         **/
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_factura);

        /**
         * Concepto de la factura
         *
         * Opciones:
         *
         * 1 = Productos 
         * 2 = Servicios 
         * 3 = Productos y Servicios
         **/
        $concepto = 1;

        /**
         * Tipo de documento del comprador
         *
         * Opciones:
         *
         * 80 = CUIT 
         * 86 = CUIL 
         * 96 = DNI
         * 99 = Consumidor Final 
         **/
        $tipo_de_documento = $this->comp->DocTipo;

        /**
         * Numero de documento del comprador (0 para consumidor final)
         **/
        $numero_de_documento = $this->comp->cuitCliente;

        /**
         * Numero de factura
         **/
        $numero_de_factura = $last_voucher+1;

        /**
         * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
         **/
        $fecha = $this->fechaHoy; // date('Y-m-d');

        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;


        $articulos = productoComprobante::Where('comprobante_id',$this->comp->id)->get();


        foreach ( $articulos as $key => $value) {

            
            if($value->iva == 21){

                $importe_gravado_al21 += round($value->precio * $value->cantidad / 1.21,2);
                $importe_iva_al21 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.21),2);

            }elseif($value->iva == 10.5){


                $importe_gravado_al105 += round($value->precio * $value->cantidad / 1.105,2);
                $importe_iva_al105 += round($value->precio * $value->cantidad - ($value->precio * $value->cantidad / 1.105),2);
            }

            // dump($importe_gravado);
            // dump($importe_iva);
        }

            /** 
         * Importe sujeto al IVA (sin icluir IVA)
         **/
        $importe_gravado = round($importe_gravado_al21 + $importe_gravado_al105,2); //  dato ejemplo 100;

        /**
         * Importe exento al IVA
         **/
        $importe_exento_iva = 0;

        /**
         * Importe de IVA
         **/
        $importe_iva = round($importe_iva_al21 + $importe_iva_al105,2); //  dato ejemplo 21;

        if($importe_gravado_al21 > 0){
            $arrayIva[] =// Alícuotas asociadas al factura
                array(
                    'Id' 		=> 5, // Id del tipo de IVA (5 = 21%)
                    'BaseImp' 	=> round($importe_gravado_al21,2),
                    'Importe' 	=> round($importe_iva_al21,2) 
                
                
                );

        }

        if($importe_gravado_al105 > 0){
            $arrayIva[] = // Alícuotas asociadas al factura
                
                array(
                    'Id' 		=> 4, // Id del tipo de IVA (4 = 105%)
                    'BaseImp' 	=> round($importe_gravado_al105,2),
                    'Importe' 	=> round($importe_iva_al105,2)
                
                );

        }


        // dd($arrayIva);

        /**
         * Los siguientes campos solo son obligatorios para los conceptos 2 y 3
         **/
        if ($concepto === 2 || $concepto === 3) {
            /**
             * Fecha de inicio de servicio en formato aaaammdd
             **/
            $fecha_servicio_desde = intval(date('Ymd'));

            /**
             * Fecha de fin de servicio en formato aaaammdd
             **/
            $fecha_servicio_hasta = intval(date('Ymd'));

            /**
             * Fecha de vencimiento del pago en formato aaaammdd
             **/
            $fecha_vencimiento_pago = intval(date('Ymd'));
        }
        else {
            $fecha_servicio_desde = null;
            $fecha_servicio_hasta = null;
            $fecha_vencimiento_pago = null;
        }

        // 1. Alícuotas de IVA
        // CÓDIGO DESCRIPCIÓN
        // 0003 0,00 %
        // 0004 10,50 %
        // 0005 21,00 %
        // 0006 27,00 %
        // 0008 5,00 %
        // 0009 2,50 %

        $data = array(
            'CantReg' 	=> 1, // Cantidad de facturas a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_factura, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_factura,
            'CbteHasta' => $numero_de_factura,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => $fecha_vencimiento_pago,
            'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva,2),
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_gravado,
            'ImpOpEx' 	=> $importe_exento_iva,
            'ImpIVA' 	=> $importe_iva,
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            'Iva' 		=> $arrayIva, 
        );
        // dd($data);

        /** 
         * Creamos la Factura 
         **/
        $res = $afip->ElectronicBilling->CreateVoucher($data,TRUE);

        /**
         * Mostramos por pantalla los datos de la nueva Factura 
         **/
        // dd(array(
        //     'cae' => $res['CAE'], //CAE asignado a la Factura
        //     'vencimiento' => $res['CAEFchVto'], //Fecha de vencimiento del CAE
        //     'res'=>$res,
        // ));

        return $res;
    }

    function ActualizarComprobante($nuevoComprobante,$descripcionTipoComp){

                //RESPUESTA DE AFIP
                $ejemplo = '{
                    "FeCabResp": {
                    "Cuit": 20409378472,
                    "PtoVta": 4,
                    "CbteTipo": 11,
                    "FchProceso": "20240331020820",
                    "CantReg": 1,
                    "Resultado": "A",
                    "Reproceso": "N"
                    },
                    "FeDetResp": {
                    "FECAEDetResponse": {
                        "Concepto": 1,
                        "DocTipo": 96,
                        "DocNro": 35833716,
                        "CbteDesde": 11,
                        "CbteHasta": 11,
                        "CbteFch": "20240331",
                        "Resultado": "A",
                        "CAE": "74132154047011",
                        "CAEFchVto": "20240410"
                    }
                    }
                }';
        


                $this->comp->cae = $nuevoComprobante->FeDetResp->FECAEDetResponse->CAE;
                $this->comp->numero = $nuevoComprobante->FeDetResp->FECAEDetResponse->CbteDesde;
                $this->comp->fechaVencimiento = $nuevoComprobante->FeDetResp->FECAEDetResponse->CAEFchVto;
                $this->comp->tipoComp = $this->tipoComprobante;
                $this->comp->fecha = Carbon::now()->format('Y-m-d H:i:s');
                $this->comp->ptoVta = $nuevoComprobante->FeCabResp->PtoVta;
                $this->comp->usuario = $this->usuario->name;





                $this->comp->save();

                // Post::create($validated);
                // $comprobante = Comprobante::create([
                    
                //     'numero' => $nuevoComprobante->FeDetResp->FECAEDetResponse->CbteDesde,
                //     'total' => round($this->total,2),
                //     'cae' => $nuevoComprobante->FeDetResp->FECAEDetResponse->CAE,
                //     'fechaVencimiento' => $nuevoComprobante->FeDetResp->FECAEDetResponse->CAEFchVto,
                //     'DocTipo'=>$nuevoComprobante->FeDetResp->FECAEDetResponse->DocTipo,
                //     'cuitCliente' => $this->cuit,
                //     'razonSocial'=>$this->razonSocial,
                //     'tipoContribuyente'=>$this->tipoContribuyente,
                //     'domicilio'=>$this->domicilio,
                //     'empresa_id'=> $this->empresa->id,
                //     'tipoComp'=>$this->tipoComprobante,
                //     'fecha'=> Carbon::now()->format('Y-m-d H:i:s'),
                //     'leyenda'=> $this->leyenda,

                //     'idFormaPago'=>$this->idFormaPago,
                //     'importeUno'=>$this->importeUno,
                //     'idFormaPago2'=>($this->idFormaPago2 == 'NO')? $this->idFormaPago :  $this->idFormaPago2 ,
                //     'importeDos'=>($this->idFormaPago2 == 'NO')? 0 :$this->importeDos ,


                //     'ptoVta'=>$nuevoComprobante->FeCabResp->PtoVta,
                //     'deposito_id'=>$this->usuario->deposito_id,
                //     'usuario'=> $this->usuario->name,
                //     'remito'=>  $this->remitoEntrega, //no (se entrega en el momento ) si (se entrega posterior)
                // ]);







                return $this->comp->id;
        }


    }
