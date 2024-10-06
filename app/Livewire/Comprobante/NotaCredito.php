<?php

namespace App\Livewire\Comprobante;

use Livewire\Component;

use Afip;
use Carbon\Carbon;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


use App\Models\Comprobante;
use App\Models\productoComprobante;

use App\Models\FormaPago;
use App\Models\Deposito;
use App\Models\Empresa;



use App\Events\DescontarStockEvent;


class NotaCredito extends Component
{

    public $empresa;
    public $comprobante;
    public $formaPago;
    public $deposito;
    public $productos;
    public $comentario;
    public $imprimir = false;


    public function mount(Comprobante $comprobante){

        $this->comprobante = $comprobante;
        $this->formaPago = FormaPago::find($comprobante->idFormaPago);
        $this->deposito = Deposito::find($comprobante->deposito_id);
        $this->productos = productoComprobante::where('comprobante_id',$comprobante->id)->get();

        $this->empresa = Empresa::find(Auth::user()->empresa_id);

    }

    public function render()
    {
        return view('livewire.comprobante.nota-credito')
        ->extends('layouts.app')
        ->section('main');
    }

    function objetoAfip(){

        if($this->empresa->fe == 'si'){
            
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

        }else{
            //cuit de pruebas 
            return new Afip(array('CUIT' => 20409378472));
        }



    }

    public function notaCredito(){
        // dd($this->comprobante->id);
        //     "id" => 16
        //     "tipoComp" => "remito"
        //     "numero" => 16
        //     "total" => 30.0
        //     "cae" => 0
        //     "fechaVencimiento" => "2024-06-22"
        //     "fecha" => "2024-06-22 11:12:35"
        //     "ptoVta" => 0
        //     "deposito_id" => 2
        //     "DocTipo" => 99
        //     "cuitCliente" => 0
        //     "razonSocial" => "Consumidor Final"
        //     "tipoContribuyente" => 5
        //     "domicilio" => null
        //     "leyenda" => null
        //     "idFormaPago" => 1
        //     "remito" => "no"
        //     "empresa_id" => 1
        //     "usuario" => "Javier Desmaret"
        //     "created_at" => "2024-06-22 11:12:35"
        //     "updated_at" => "2024-06-22 11:12:35"
        //   ]

        switch ($this->comprobante->tipoComp) {
            case 1: // FACTURA A
                # code...
                // dd('factura a');
                $notaCredito = $this->notaCreditoA();
                $descripcionTipoComp = 'NC A';

                break;
            case 11:
                # code...
                $notaCredito = $this->notaCreditoC();
                $descripcionTipoComp = 'NC C';
                break;
            case 6:
                # code...
                $notaCredito = $this->notaCreditoB();
                $descripcionTipoComp = 'NC B';
                break;
            case 'remito':
                # code...
                $notaCredito = $this->notaRemito();
                $descripcionTipoComp = 'NOTA REMITO';
                break;
            
            default:
                # code...
                dd('Error');
                break;
        }
                // {#1596 ▼ // app/Livewire/Comprobante/NotaCredito.php:144
                    //     +"FeCabResp": {#1725 ▼
                    //         +"Cuit": 20409378472
                    //         +"PtoVta": 4
                    //         +"CbteTipo": 3
                    //         +"FchProceso": "20240622185210"
                    //         +"CantReg": 1
                    //         +"Resultado": "A"
                    //         +"Reproceso": "N"
                    //     }
                    //     +"FeDetResp": {#1720 ▼
                    //         +"FECAEDetResponse": {#1724 ▼
                    //         +"Concepto": 1
                    //         +"DocTipo": 80
                    //         +"DocNro": 30999251524
                    //         +"CbteDesde": 5
                    //         +"CbteHasta": 5
                    //         +"CbteFch": "20240622"
                    //         +"Resultado": "A"
                    //         +"Observaciones": {#1721 ▶}
                    //         +"CAE": "74252179206219"
                    //         +"CAEFchVto": "20240702"
                    //         }
                    //     }
                //     }

                $notaGuardada = Comprobante::create([
            
                    'numero' => $notaCredito->FeDetResp->FECAEDetResponse->CbteDesde,
                    'total' => round($this->comprobante->total * -1 ,2), //PARA QUE EL IMPORTE RESTE A LOS COMPROBANTES AJUSTAR EN LA IMPRECION
                    'cae' => $notaCredito->FeDetResp->FECAEDetResponse->CAE,
                    'fechaVencimiento' => $notaCredito->FeDetResp->FECAEDetResponse->CAEFchVto,
                    'DocTipo'=>$notaCredito->FeDetResp->FECAEDetResponse->DocTipo,
                    'cuitCliente' => $this->comprobante->cuitCliente,
                    'razonSocial'=>$this->comprobante->razonSocial,
                    'tipoContribuyente'=>$this->comprobante->tipoContribuyente,
                    'domicilio'=>$this->comprobante->domicilio,
                    'empresa_id'=> $this->empresa->id,
                    'tipoComp'=>$notaCredito->FeCabResp->CbteTipo,
                    'fecha'=> Carbon::now()->format('Y-m-d H:i:s'),
                    'leyenda'=> $this->comentario,
                    'idFormaPago'=>$this->comprobante->idFormaPago,
                    'ptoVta'=>$notaCredito->FeCabResp->PtoVta,
                    'deposito_id'=>$this->comprobante->deposito_id,
                    'usuario'=> Auth::user()->name,
                    'remito'=>'no', //no (se entrega en el momento ) si (se entrega posterior)
                ]);

                foreach ($this->productos as $key => $value) {

                    productoComprobante::create([
                        'comprobante_id'=> $notaGuardada->id,
                        'comprobante_numero'=>$notaGuardada->numero,
                        'codigo'=>$value['codigo'],
                        'detalle'=>$value['detalle'],
                        'precio'=>$value['precio'],
                        'costo'=>$value['costo'],

                        'iva'=>$value['iva'],
                        'cantidad'=>$value['cantidad'],
                        'rubro'=>$value['rubro'],
                        'proveedor'=>$value['proveedor'],
                        'marca'=>$value['marca'],

                        'tipoComp'=>$notaGuardada->tipoComp,
                        'fecha'=>$notaGuardada->fecha,
                        'idFormaPago'=>$notaGuardada->idFormaPago,
                        'ptoVta'=>$notaCredito->FeCabResp->PtoVta,
                        'usuario'=> Auth::user()->name,
                        'empresa_id'=> $this->empresa->id,
                    ]);
    
                    if($value['controlStock'] == 'si'){  //no (se entrega en el momento ) si (se entrega posterior)
                        //AK DESCUENTA EL STOCK
                        DescontarStockEvent::dispatch([
                            'codigo'=>$value['codigo'],
                            'detalle'=>$value['detalle'],
                            'deposito_id'=>$this->comprobante->deposito_id,
                            'stock'=>($value['cantidad']),
                            'comentario'=>'Ingreso '.$descripcionTipoComp.' N-'.$notaGuardada->numero,
                            'usuario'=>Auth::user()->name,
                            'empresa_id'=>$this->empresa->id,
            
                        ]);
                    }else{
                        //AK  EL REMITO CUANDO SE GERENERE DESCUENTA EL STOCK
                    }
    
    
    
        
                }

                if($this->imprimir){

                    $this->redirectRoute('formatoPDF',['comprobante_id'=>$notaGuardada->id,'tipo'=>'factura']);

                    // $this->redirectRoute('formatoPDF',['comprobante_id'=>$comprobanteId,
                    // 'tipo'=>'presupuesto']);
        
                }else{
        
                    
                    $this->redirectRoute('comprobante');
        
                }

    }

    function notaCreditoA(){

        // dd();
        /**
         * Numero del punto de venta
         **/
        $punto_de_venta = Auth::user()->puntoVenta;// 

        /**
         * Tipo de Nota de Crédito
         **/
        $tipo_de_nota = 3; // 3 = Nota de Crédito A

        $afip = $this->objetoAfip();
        /**
         * Número de la ultima Nota de Crédito A
         **/
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_nota);

        /**
         * Numero del punto de venta de la Factura 
         * asociada a la Nota de Crédito
         **/
        $punto_factura_asociada = $this->comprobante->ptoVta;

        /**
         * Tipo de Factura asociada a la Nota de Crédito
         **/
        $tipo_factura_asociada = 1; // 1 = Factura A

        /**
         * Numero de Factura asociada a la Nota de Crédito
         **/
        $numero_factura_asociada = $this->comprobante->numero;

        /**
         * Concepto de la Nota de Crédito
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
        $tipo_de_documento = $this->comprobante->DocTipo;

        /**
         * Numero de documento del comprador (0 para consumidor final)
         **/
        $numero_de_documento = $this->comprobante->cuitCliente;

        /**
         * Numero de Nota de Crédito
         **/
        $numero_de_nota = $last_voucher+1;

        /**
         * Fecha de la Nota de Crédito en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
         **/
        $fecha = date('Y-m-d');

        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;

        foreach ( $this->productos as $key => $value) {
            // array:6 [▼ // app/Livewire/Factura/NuevoComprobante.php:815
            //     "codigo" => "06323325"
            //     "detalle" => "ipsa"
            //     "precio" => 65.67
            //     "iva" => 10.5
            //     "cantidad" => 1
            //     "subtotal" => 65.67
            // ]
            
            if($value['iva'] == 21){

                $importe_gravado_al21 += round($value['precio'] * $value['cantidad'] / 1.21,2);
                $importe_iva_al21 += round($value['precio'] * $value['cantidad'] - ($value['precio'] * $value['cantidad'] / 1.21),2);

            }elseif($value['iva'] == 10.5){

                $importe_gravado_al105 += round($value['precio'] * $value['cantidad'] / 1.105,2);
                $importe_iva_al105 += round($value['precio'] * $value['cantidad'] - ($value['precio'] * $value['cantidad'] / 1.105),2);
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
            'CantReg' 	=> 1, // Cantidad de Notas de Crédito a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_nota, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_nota,
            'CbteHasta' => $numero_de_nota,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => $fecha_vencimiento_pago,
            'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva,2),//$importe_gravado + $importe_iva + $importe_exento_iva,
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_gravado,
            'ImpOpEx' 	=> $importe_exento_iva,
            'ImpIVA' 	=> $importe_iva,
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en la Nota de Crédito ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)
            'CbtesAsoc' => array( //Factura asociada
                array(
                    'Tipo' 		=> $tipo_factura_asociada,
                    'PtoVta' 	=> $punto_factura_asociada,
                    'Nro' 		=> $numero_factura_asociada,
                )
            ),
            'Iva'=> $arrayIva, 
        );

        /** 
         * Creamos la Nota de Crédito 
         **/
        $res = $afip->ElectronicBilling->CreateVoucher($data,true);

        /**
         * Mostramos por pantalla los datos de la nueva Nota de Crédito 
         **/
        // dd($res);
        return $res;
        // dd(array(
        //     'cae' => $res['CAE'], //CAE asignado a la Nota de Crédito
        //     'vencimiento' => $res['CAEFchVto'] //Fecha de vencimiento del CAE
        // ));



    }

    
    function notaCreditoB(){

        /**
         * Numero del punto de venta
         **/
        $punto_de_venta = Auth::user()->puntoVenta;// 

        /**
         * Tipo de Nota de Crédito
         **/
        $tipo_de_nota = 8; // 8 = Nota de Crédito B

        $afip = $this->objetoAfip();
        /**
         * Número de la ultima Nota de Crédito B
         **/
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_nota);

        /**
         * Numero del punto de venta de la Factura 
         * asociada a la Nota de Crédito
         **/
        $punto_factura_asociada = $this->comprobante->ptoVta;

        /**
         * Tipo de Factura asociada a la Nota de Crédito
         **/
        $tipo_factura_asociada = 6; // 6 = Factura B

        /**
         * Numero de Factura asociada a la Nota de Crédito
         **/
        $numero_factura_asociada = $this->comprobante->numero;

        /**
         * Concepto de la Nota de Crédito
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
        $tipo_de_documento = $this->comprobante->DocTipo;

        /**
         * Numero de documento del comprador (0 para consumidor final)
         **/
        $numero_de_documento = $this->comprobante->cuitCliente;

        /**
         * Numero de Nota de Crédito
         **/
        $numero_de_nota = $last_voucher+1;

        /**
         * Fecha de la Nota de Crédito en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
         **/
        $fecha = date('Y-m-d');

        $importe_gravado_al21=0;
        $importe_iva_al21=0;
        $importe_gravado_al105=0;
        $importe_iva_al105=0;

        foreach ( $this->productos as $key => $value) {
            // array:6 [▼ // app/Livewire/Factura/NuevoComprobante.php:815
            //     "codigo" => "06323325"
            //     "detalle" => "ipsa"
            //     "precio" => 65.67
            //     "iva" => 10.5
            //     "cantidad" => 1
            //     "subtotal" => 65.67
            // ]
            
            if($value['iva'] == 21){

                $importe_gravado_al21 += round($value['precio'] * $value['cantidad'] / 1.21,2);
                $importe_iva_al21 += round($value['precio'] * $value['cantidad'] - ($value['precio'] * $value['cantidad'] / 1.21),2);

            }elseif($value['iva'] == 10.5){

                $importe_gravado_al105 += round($value['precio'] * $value['cantidad'] / 1.105,2);
                $importe_iva_al105 += round($value['precio'] * $value['cantidad'] - ($value['precio'] * $value['cantidad'] / 1.105),2);
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
            'CantReg' 	=> 1, // Cantidad de Notas de Crédito a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_nota, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_nota,
            'CbteHasta' => $numero_de_nota,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => $fecha_vencimiento_pago,
            'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva,2),//$importe_gravado + $importe_iva + $importe_exento_iva,
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_gravado,
            'ImpOpEx' 	=> $importe_exento_iva,
            'ImpIVA' 	=> $importe_iva,
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en la Nota de Crédito ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            'CbtesAsoc' => array( //Factura asociada
                array(
                    'Tipo' 		=> $tipo_factura_asociada,
                    'PtoVta' 	=> $punto_factura_asociada,
                    'Nro' 		=> $numero_factura_asociada,
                )
            ),
            'Iva' 		=> $arrayIva, 
        );

        /** 
         * Creamos la Nota de Crédito 
         **/
        $res = $afip->ElectronicBilling->CreateVoucher($data,true);

        /**
         * Mostramos por pantalla los datos de la nueva Nota de Crédito 
         **/
        return $res;

    }

 
    function notaCreditoC(){

        /**
         * Numero del punto de venta
         **/
        $punto_de_venta = Auth::user()->puntoVenta;// 

        /**
         * Tipo de Nota de Crédito
         **/
        $tipo_de_nota = 13; // 13 = Nota de Crédito C

        $afip = $this->objetoAfip();

        /**
         * Número de la ultima Nota de Crédito C
         **/
        $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_nota);

        /**
         * Numero del punto de venta de la Factura 
         * asociada a la Nota de Crédito
         **/
        $punto_factura_asociada = $this->comprobante->ptoVta;

        /**
         * Tipo de Factura asociada a la Nota de Crédito
         **/
        $tipo_factura_asociada = 11; // 11 = Factura C

        /**
         * Numero de Factura asociada a la Nota de Crédito
         **/
        $numero_factura_asociada = $this->comprobante->numero;

        /**
         * Concepto de la Nota de Crédito
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
        $tipo_de_documento = $this->comprobante->DocTipo;

        /**
         * Numero de documento del comprador (0 para consumidor final)
         **/
        $numero_de_documento = $this->comprobante->cuitCliente;

        /**
         * Numero de comprobante
         **/
        $numero_de_nota = $last_voucher+1;

        /**
         * Fecha de la Nota de Crédito en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
         **/
        $fecha = date('Y-m-d');

        /**
         * Importe de la Nota de Crédito
         **/
        $importe_total = round($this->comprobante->total,2);

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
            'CantReg' 	=> 1, // Cantidad de Notas de Crédito a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_nota, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_nota,
            'CbteHasta' => $numero_de_nota,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => $fecha_vencimiento_pago,
            'ImpTotal' 	=> $importe_total,
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_total, // Importe neto
            'ImpOpEx' 	=> 0, // Importe exento al IVA
            'ImpIVA' 	=> 0, // Importe de IVA
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en el comprobante ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
            'CbtesAsoc' => array( //Factura asociada
                array(
                    'Tipo' 		=> $tipo_factura_asociada,
                    'PtoVta' 	=> $punto_factura_asociada,
                    'Nro' 		=> $numero_factura_asociada,
                )
            )
        );

        /** 
         * Creamos la Nota de Crédito 
         **/
        $res = $afip->ElectronicBilling->CreateVoucher($data,true);

        /**
         * Mostramos por pantalla los datos de la nueva Nota de Crédito 
         **/
        return $res;
    }

    //no funciona falta
    function notaRemito(){

        // Obtener el último registro
        $ultimoRegistro = Comprobante::latest()->first();

        if ($ultimoRegistro) {
            $ultimoId = $ultimoRegistro->id + 1;
            // echo "El último ID es: " . $ultimoId;
        } else {
            // echo "No hay registros en la tabla.";
            $ultimoId =  1;
        }

        return json_decode(
            '{
                "FeCabResp": {
                "Cuit": '.$this->empresa->cuit.',
                "PtoVta": '.$this->comprobante->ptoVta.',
                "CbteTipo": "notaRemito",
                "FchProceso": "0",
                "CantReg": 1,
                "Resultado": "A",
                "Reproceso": "N"
                },
                "FeDetResp": {
                "FECAEDetResponse": {
                    "Concepto": 1,
                    "DocTipo": '.$this->comprobante->DocTipo.',
                    "DocNro": '.$this->comprobante->cuitCliente.',
                    "CbteDesde": '.$ultimoId .',
                    "CbteHasta": '.$ultimoId .',
                    "CbteFch": "0",
                    "Resultado": "A",
                    "CAE": "0",
                    "CAEFchVto": "'. Carbon::now()->format('Y-m-d H:i:s').'"
                }
                }
            }');

    }


}
