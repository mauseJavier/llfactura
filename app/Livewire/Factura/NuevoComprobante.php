<?php

namespace App\Livewire\Factura;


use Afip;
use Carbon\Carbon;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\DB;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;


use App\Models\Comprobante;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\FormaPago;
use App\Models\productoComprobante;
use App\Models\Inventario;



use App\Models\Presupuesto;
use App\Models\ProductoPresupuesto;


use Illuminate\Support\Facades\Auth;

use App\Events\DescontarStockEvent;
use App\Events\SaldoCuentaCorriente;

//PARA ESCUCHAR EVENTOS 
use Livewire\Attributes\On; 

class NuevoComprobante extends Component
{

    public $fechaHoy;
    public $fechaMin;
    public $fechaMax;

    // #[Validate('required|numeric|min:0|max:99999999999', message: 'Compruebe si el Cuit es igual a 0, o tener 11 digitos y ser numeros.')]

    public $cuit;
    public $razonSocial;
    public $total;
    public $tipoDocumento;
    public $tipoComprobante;
    public $tipoContribuyente;
    public $domicilio;
    public $correoCliente;
    public $leyenda;

    public $formaPago;
    public $formaPago2;

    public $idFormaPago;
    public $idFormaPago2;

    public $importeUno;
    public $importeDos;

    public $empresa;
    public $usuario;


    public $imprimir;
    public $remitoEntrega;



    //VARIABLES PARA PARA LA VENTA RAPIDA DE ARTICULO FAVORITO
    public $codigo ;
    public $detalle ;
    public $rubro ;
    public $proveedor ;
    public $marca;
    public $ivaDefecto ;


    //para cuando el total viene del carrito no se pueda editar el monto a mano 
    public $modificarImporte='';


    #[Session(key: 'carrito')] 
    public $carrito;

    #[Session(key: 'cliente')] 
    public $cliente;

  

    public function igualarTotal(){
        $this->importeUno = $this->total;
        $this->importeDos = 0;
        $this->idFormaPago2 = 'NO';


    }

    public function igualarTotalImporteUno(){

        $this->importeUno = $this->total;
        $this->importeDos = 0;
        $this->idFormaPago2 = 'NO';

    }


    #[On('seleccionarCliente')] 
    public function seleccionarCliente(Cliente $cliente){

        // array:10 [▼
        //     "id" => 7
        //     "tipoDocumento" => 99
        //     "tipoContribuyente" => 5
        //     "numeroDocumento" => 5555333
        //     "razonSocial" => "martin"
        //     "domicilio" => ""
        //     "correo" => ""
        //     "empresa_id" => 1
        //     "created_at" => "2024-06-30 00:30:55"
        //     "updated_at" => "2024-06-30 00:30:55"
        // ]

        $this->domicilio = $cliente->domicilio;
        $this->correoCliente = $cliente->correo;
        $this->tipoDocumento = $cliente->tipoDocumento;
        $this->razonSocial = $cliente->razonSocial;
        $this->tipoContribuyente = $cliente->tipoContribuyente;
        $this->cuit = $cliente->numeroDocumento;

        $this->cambiarFactura();



    }


    public function cargarFavorito($codigo,$detalle,$rubro,$proveedor,$marca,$iva){

        $this->codigo = $codigo ;
        $this->detalle = $detalle ;
        $this->rubro = $rubro ;
        $this->proveedor = $proveedor ;
        $this->marca = $marca;
        $this->ivaDefecto = $iva ;


        $this->render();
    }

    public function facturar(Request $request)//este request es por la session 
    {

        // dd($this->importeUno .' '. $this->importeDos);

        $validated = $this->validate([
            'cuit' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {

                    if($this->tipoComprobante == 1 OR $this->tipoComprobante == 51){

                        if(strlen($value) == 11) {
                            return true;
                        }else{
                            $fail('Para Factura A o M ingrese CUIT del Cliente');
                        }

                    }else{

                        if ($value == 0) {
                            return true;
                        }elseif(strlen($value) == 11 OR strlen($value) == 8) {
                            return true;
                        }else{
                            $fail('El número debe tener 11 o 8 caracteres.');
                        }


                    }


                },
            ],


            'importeUno' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value == 0) {
                        $fail('El número debe ser mayor a 0.');
                    }
                },

            ],

            'importeDos' => [
                'required',
                'numeric',

            ],

            'total' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    if ($value == 0) {
                        $fail('El número debe ser mayor a 0.');
                    }
                    // Validación para verificar que total >= (importeUno + importeDos)
                    if(is_numeric($this->importeUno) AND is_numeric($this->importeDos)){

                        if ((doubleVal($value) - doubleVal($this->importeUno + $this->importeDos)) != 0) {
                            $fail('Diferencia : $'.number_format ($value - ($this->importeUno + $this->importeDos),2) .' en la Pago. El PAGO debe ser justo.' );
                        }
                    }
                    else{
                        $fail('Importe Uno y Dos Numerico');

                    }

                },
            ],

            'razonSocial' => [
                'required',
                'min:1',
                'max:256',
            ],
            'tipoDocumento' => [
                'required',

            ],
        ],[
            'required' => 'El campo :attribute es obligatorio.',
            'numeric' => 'El campo :attribute debe ser un numero válido.',
        ]);



        // AK TENEMOS QUE SABER SI VAMOS A HACER A B C O REMITO PRESUPUESTO    
        if($this->tipoComprobante == 11){
            $descripcionTipoComp = 'Factura C';
            $nuevoComprobante = $this->crearComprobanteC($this->total,$this->cuit,$this->tipoDocumento);
            $comprobanteId = $this->finalizarComprobante($nuevoComprobante,$descripcionTipoComp);            
        }elseif($this->tipoComprobante == 6){
            $descripcionTipoComp = 'Factura B';
            $nuevoComprobante = $this->crearComprobanteB($this->total,$this->tipoDocumento,$this->cuit);
            $comprobanteId = $this->finalizarComprobante($nuevoComprobante,$descripcionTipoComp);
        }elseif($this->tipoComprobante == 1){
            $descripcionTipoComp = 'Factura A';
            $nuevoComprobante = $this->crearComprobanteA($this->total,$this->tipoDocumento,$this->cuit);
            $comprobanteId = $this->finalizarComprobante($nuevoComprobante,$descripcionTipoComp);

        }elseif($this->tipoComprobante == 51){
            $descripcionTipoComp = 'Factura M';
            $nuevoComprobante = $this->crearComprobanteM($this->total,$this->tipoDocumento,$this->cuit);
            $comprobanteId = $this->finalizarComprobante($nuevoComprobante,$descripcionTipoComp);

        }elseif($this->tipoComprobante == 'remito'){
            $descripcionTipoComp = 'Remito';

            
            // Obtener el último registro
            $ultimoRegistro = Comprobante::latest()->first();

            if ($ultimoRegistro) {
                $ultimoId = $ultimoRegistro->id + 1;
                // echo "El último ID es: " . $ultimoId;
            } else {
                // echo "No hay registros en la tabla.";
                $ultimoId =  1;
            }

            if( !isset($this->carrito['total'])){

                $this->carrito['carrito'][] = array(
                    'codigo'=>$this->codigo,
                    'detalle'=>$this->detalle,
                    'rubro'=>$this->rubro,
                    'proveedor'=>$this->proveedor,
                    'marca'=>$this->marca,
                    'iva'=>$this->ivaDefecto,
                    
                    'porcentaje'=> 0,
                    'precioLista'=> round($this->total,2) ,
                    'descuento'=> 0 ,
                    
                    'costo'=> 0 , //SI CONTROLES DE COSTO Y STOCK POR QUE NO SABEMOS QUE CANTIDAD SE VENDE
                    'controlStock'=>'no', //SI CONTROLES DE COSTO Y STOCK POR QUE NO SABEMOS QUE CANTIDAD SE VENDE

                    
                    'precio'=> round($this->total,2),
                    'cantidad'=>1,

                    'subtotal'=>  round($this->total,2),
    
                    ) ;
                $this->carrito['total']= round($this->total,2);
            }

            $nuevoComprobante = json_decode(
                '{
                    "FeCabResp": {
                    "Cuit": '.$this->empresa->cuit.',
                    "PtoVta": 0,
                    "CbteTipo": "remito",
                    "FchProceso": "0",
                    "CantReg": 1,
                    "Resultado": "A",
                    "Reproceso": "N"
                    },
                    "FeDetResp": {
                    "FECAEDetResponse": {
                        "Concepto": 1,
                        "DocTipo": '.$this->tipoDocumento.',
                        "DocNro": '.$this->cuit.',
                        "CbteDesde": '.$ultimoId .',
                        "CbteHasta": '.$ultimoId .',
                        "CbteFch": "0",
                        "Resultado": "A",
                        "CAE": "0",
                        "CAEFchVto": "'.$this->fechaHoy.'"
                    }
                    }
                }');
            $comprobanteId = $this->finalizarComprobante($nuevoComprobante,$descripcionTipoComp);

        }elseif($this->tipoComprobante == 'presupuesto'){
            $comprobanteId = $this->finalizarPresupuesto(); 
        }else{
            dd('Tipo de comprobante erroneo: '.$this->tipoComprobante);
        }


        //borramos la session de carrito 
        $this->carrito=null;
        $this->cliente=null;


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



    public function modificarFormaPago2(){

        $this->formaPago2 = FormaPago::where('id','!=',$this->idFormaPago)                                        
        ->get();

    }

    public function mount (){

        //datos del cliente
        $this->cuit=0;
        $this->tipoDocumento=99;
        $this->razonSocial='Consumidor Final';

        if(isset($this->carrito['total'])){
            $this->total = $this->carrito['total'];
            $this->modificarImporte='disabled';

            $this->importeUno = $this->carrito['total'];


        }else{
            $this->total = 0;
            $this->importeUno = 0;

        }

        $this->importeDos = 0;

        // $now = Carbon::now()->format('Y-m-d H:i:s');
        // echo $now->format('Y-m-d H:i:s'); 

        $this->fechaHoy = date('Y-m-d');
        $fecha_restada = strtotime("-5 days", strtotime($this->fechaHoy));
        $this->fechaMin = date("Y-m-d", $fecha_restada);
        $this->fechaMax = $this->fechaHoy;


        //datos de empresa
        // $titles = DB::table('users')->pluck('title');
        $this->empresa = Empresa::find(Auth::user()->empresa_id);
        $this->usuario = Auth::user();

        if( $this->empresa->iva == 'ME' AND $this->empresa->fe == 'si'){
            $this->tipoComprobante = 11;
        }elseif($this->empresa->iva == 'RI' AND $this->empresa->fe == 'si'){
            $this->tipoComprobante = 6;
        }elseif($this->empresa->fe == 'no' AND isset($this->carrito['total'])){
            $this->tipoComprobante = 'remito';
        }else{
            $this->tipoComprobante = 11;
        }


        $this->tipoContribuyente=5;



        $this->formaPago = FormaPago::all();
        $this->idFormaPago = $this->empresa->idFormaPago;
        $this->idFormaPago2 = 'NO';

        $this->imprimir = $this->empresa->imprimirSiNo;

        $this->formaPago2 = FormaPago::where('id','!=',$this->idFormaPago)                                        
                                        ->get();


        $this->remitoEntrega = 'no'; //no (se entrega en el momento ) si (se entrega posterior)


        //PARA CARGAR EL CLIENTE EN EL CASO DE QUE SE CARGUE UN PRESUPUESTO 
        if(isset($this->cliente['razonSocial'])){

            $this->cuit = $this->cliente['cuitCliente'];
            $this->razonSocial = $this->cliente['razonSocial'];
            $this->tipoDocumento = $this->cliente['DocTipo'];
            $this->tipoContribuyente = $this->cliente['tipoContribuyente'];
            $this->domicilio = $this->cliente['domicilio'];
            $this->leyenda = $this->cliente['leyenda'];
            $this->idFormaPago = $this->cliente['idFormaPago'];

        }

        //variables venta favoritos 
        $this->codigo = 'Varios';
        $this->detalle = 'Varios';
        $this->rubro = 'General';
        $this->proveedor = 'General' ;
        $this->marca = 'General' ;
        $this->ivaDefecto = $this->empresa->ivaDefecto;
    
    }


    
    public function render()
    {



        return view('livewire.factura.nuevoComprobante',
            [
            'fechaHoy'=> $this->fechaHoy,
            'fechaMin'=> $this->fechaMin ,
            'fechaMax'=> $this->fechaMax,
            'favoritos'=> Inventario::where('empresa_id',Auth()->user()->empresa_id)
                                    ->where('favorito',true)->get(),
            
            
            ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

    function buscarCuit(){

            // // Certificado (Puede estar guardado en archivos, DB, etc)
            // $cert = file_get_contents('./certificado.crt');

            // // Key (Puede estar guardado en archivos, DB, etc)
            // $key = file_get_contents('./key.key');

            if (Storage::disk('local')->exists('public/'.$this->empresa->cuit.'/cert.crt') ) {
                // ...
                $cert = Storage::get('public/'.$this->empresa->cuit.'/cert.crt');

                // return response()->json($cert, 200);
                
            }else
            {
                dd('No existe CERT');
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
                'CUIT' =>  $this->empresa->cuit,
                'cert' => $cert,
                'key' => $key,
                'access_token' => env('tokenAFIPsdk'),
                'production' => TRUE
            ));
          
        // CUIT del contribuyente
        // $tax_id = 20111111111;

        $taxpayer_details = $afip->RegisterInscriptionProof->GetTaxpayerDetails($this->cuit); 

            // {#1632 ▼ // app/Livewire/Factura/Factura.php:199
            // +"datosGenerales": {#1635 ▼
            //         +"domicilioFiscal": {#1636 ▼
            //         +"codPostal": "8347"
            //         +"descripcionProvincia": "NEUQUEN"
            //         +"direccion": "SAAVEDRA 474"
            //         +"idProvincia": 20
            //         +"localidad": "LAS LAJAS"
            //         +"tipoDomicilio": "FISCAL"
            //         }
            //     +"esSucesion": "NO"
            //     +"estadoClave": "ACTIVO"
            //     +"fechaContratoSocial": "1924-01-01T16:00:00.000Z"
            //     +"idPersona": 30999251524
            //     +"mesCierre": 12
            //     +"razonSocial": "MUNICIPALIDAD DE LAS LAJAS"
            //     +"tipoClave": "CUIT"
            //     +"tipoPersona": "JURIDICA"
            // }
            // +"datosRegimenGeneral": {#1628 ▼
            //     +"actividad": array:2 [▼
            //     0 => {#1629 ▼
            //         +"descripcionActividad": "MATANZA DE GANADO BOVINO"
            //         +"idActividad": 101011
            //         +"nomenclador": 883
            //         +"orden": 2
            //         +"periodo": 201311
            //     }
            //     1 => {#1631 ▶}
            //     ]
            //     +"impuesto": array:2 [▼
            //     0 => {#1627 ▼
            //         +"descripcionImpuesto": "IVA EXENTO"
            //         +"idImpuesto": 32
            //         +"periodo": 201509
            //     }
            //     1 => {#1626 ▼
            //         +"descripcionImpuesto": "EMPLEADOR-APORTES SEG. SOCIAL"
            //         +"idImpuesto": 301
            //         +"periodo": 201101
            //     }
            //     ]
            // }
            // +"metadata": {#1625 ▼
            //     +"fechaHora": "2024-03-31T05:57:10.614Z"
            //     +"servidor": "linux11d"
            // }
            // }

            // dd($taxpayer_details);

            // PARA LAS EMPRESAS O ENTES 
            if(isset($taxpayer_details->datosGenerales->razonSocial)){
                $this->razonSocial = $taxpayer_details->datosGenerales->razonSocial;
                $this->tipoDocumento = 80;
                $this->domicilio =  $taxpayer_details->datosGenerales->domicilioFiscal->direccion .' '.
                                    $taxpayer_details->datosGenerales->domicilioFiscal->localidad .' '.
                                    $taxpayer_details->datosGenerales->domicilioFiscal->descripcionProvincia;
                // descripcionProvincia": "BUENOS AIRES",
                // "direccion": "COLECTORA ESTE 34903",
                // "idProvincia": 1,
                // "localidad": "RICARDO ROJAS",

                // PARA LOS MONOTRIBUTISTAS 
            }elseif(isset($taxpayer_details->datosGenerales->apellido)){
                $apellidoNombre='';
                if(isset($taxpayer_details->datosGenerales->apellido)){
                    $apellidoNombre .= $taxpayer_details->datosGenerales->apellido;
                }
                if(isset($taxpayer_details->datosGenerales->nombre)){
                    $apellidoNombre .= ' '. $taxpayer_details->datosGenerales->nombre;

                }
                $this->razonSocial = $apellidoNombre;
                $this->tipoDocumento = 80;
                $this->domicilio =  $taxpayer_details->datosGenerales->domicilioFiscal->direccion .' '.
                $taxpayer_details->datosGenerales->domicilioFiscal->localidad .' '.
                $taxpayer_details->datosGenerales->domicilioFiscal->descripcionProvincia;

                // PARA LOS CUILS 
            }elseif(isset($taxpayer_details->errorConstancia->apellido)){

                // {#1635 ▼ // app/Livewire/Factura/Factura.php:250
                //     +"errorConstancia": {#1636 ▼
                //     +"apellido": "DESMARET"
                //     +"error": array:1 [▼
                //     0 => "La clave ingresada no es una CUIT ni una CDI"
                //     ]
                //     +"idPersona": 20358337164
                //     +"nombre": "JAVIER NICOLAS"
                // }
                // +"metadata": {#1632 ▼
                //     +"fechaHora": "2024-03-31T06:26:03.185Z"
                //     +"servidor": "linux11e"
                // }
                // }

                if(isset($taxpayer_details->errorConstancia->apellido)){
                    $apellidoNombre='';
                    if(isset($taxpayer_details->errorConstancia->apellido)){
                        $apellidoNombre .= $taxpayer_details->errorConstancia->apellido;
                    }
                    if(isset($taxpayer_details->errorConstancia->nombre)){
                        $apellidoNombre .= ' '. $taxpayer_details->errorConstancia->nombre;

                    }
                    $this->razonSocial = $apellidoNombre;
                }else{
                    $this->razonSocial='Consumidor Final';
                    $this->tipoDocumento = 99;
                }
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

                 $this->tipoDocumento = 86;
            }else{
                $this->razonSocial='Consumidor Final';
                $this->tipoDocumento = 99;
            }


    }

    function buscarCliente(){

        $clienteBuscado = Cliente::where('numeroDocumento',$this->cuit)
                                    ->where('empresa_id',$this->empresa->id)
                                    ->firstOr(function () {
            $this->domicilio = '';   
            $this->correoCliente = '';
            $this->tipoDocumento=99; 
            $this->razonSocial='Consumidor Final';
        });

        if($clienteBuscado != null){
            $this->domicilio = $clienteBuscado->domicilio;
            $this->correoCliente = $clienteBuscado->correo;
            $this->tipoDocumento = $clienteBuscado->tipoDocumento;
            $this->razonSocial = $clienteBuscado->razonSocial;
            $this->tipoContribuyente = $clienteBuscado->tipoContribuyente;

        }else{
            $this->buscarCuit();
        }

        $this->cambiarFactura();

        // 

    }

    public function cambiarFactura(){
        // <option value="5">Consumidor Final</option>
        // <option value="13">Monotributista</option>
        // <option value="6">Responsable Inscripto</option>
        // <option value="4">Exento</option>

        // <option value="11">Factura C</option>
        // <option value="6">Factura B</option>
        // <option value="1">Factura A</option>    
        // <option value="51">Factura M</option>   

        // dd($this->empresa->iva);
        if($this->tipoContribuyente == 6 AND $this->empresa->iva == 'RI'){
            $this->tipoComprobante = 1;
            // dd('entra');
        }else {
            // dd('hola');
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

    function crearComprobanteC($total,$cuit,$tipoDocumento){


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
                $tipo_de_documento = $tipoDocumento;

                /**
                 * Numero de documento del comprador (0 para consumidor final)
                 **/
                $numero_de_documento = $cuit;

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
                $importe_total = 0;

                if( !isset($this->carrito['total'])){

                    $this->carrito['carrito'][] = array(
                        'codigo'=>$this->codigo,
                        'detalle'=>$this->detalle,
                        'rubro'=>$this->rubro,
                        'proveedor'=>$this->proveedor,
                        'marca'=>$this->marca,
                        'iva'=>$this->ivaDefecto,

                        'porcentaje'=> 0,
                        'precioLista'=> round($total,2) ,
                        'descuento'=> 0 ,
                        'costo'=> 0 ,

                        'precio'=> round($total,2),
                        'cantidad'=>1,
                        'controlStock'=>'no',
                        'subtotal'=>  round($total,2),
        
                        ) ;
                    $this->carrito['total']= round($total,2);
                }
    
               
                foreach ( $this->carrito['carrito'] as $key => $value) {
                    // array:6 [▼ // app/Livewire/Factura/NuevoComprobante.php:815
                    //     "codigo" => "06323325"
                    //     "detalle" => "ipsa"
                    //     "precio" => 65.67
                    //     "iva" => 10.5
                    //     "cantidad" => 1
                    //     "subtotal" => 65.67
                    // ]

                    $importe_total += round($value['precio'] * $value['cantidad'],2);
                    
    
                    // dump($importe_gravado);
                    // dump($importe_iva);
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

    function crearComprobanteB($total,$tipoDocumento,$cuit,){

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
            $tipo_de_documento = $tipoDocumento;

            /**
             * Numero de documento del comprador (0 para consumidor final)
             **/
            $numero_de_documento =$cuit;

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

            if( !isset($this->carrito['total'])){

                $this->carrito['carrito'][] = array(
                    'codigo'=>$this->codigo,
                    'detalle'=>$this->detalle,
                    'rubro'=>$this->rubro,
                    'proveedor'=>$this->proveedor,
                    'marca'=>$this->marca,
                    'iva'=>$this->ivaDefecto,

                    'porcentaje'=> 0,
                    'precioLista'=> round($total,2) ,
                    'descuento'=> 0 ,
                    'costo'=> 0 ,

                    'precio'=> round($total,2),
                    'cantidad'=>1,
                    'controlStock'=>'no',
                    'subtotal'=>  round($total,2),
    
                    ) ;
                $this->carrito['total']= round($total,2);
            }

           
            foreach ( $this->carrito['carrito'] as $key => $value) {
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

    function crearComprobanteA($total,$tipoDocumento,$cuit,){

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
            $tipo_de_documento = $tipoDocumento;

            /**
             * Numero de documento del comprador (0 para consumidor final)
             **/
            $numero_de_documento =$cuit;

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

           
            if( !isset($this->carrito['total'])){

                $this->carrito['carrito'][] = array(
                    'codigo'=>$this->codigo,
                    'detalle'=>$this->detalle,
                    'rubro'=>$this->rubro,
                    'proveedor'=>$this->proveedor,
                    'marca'=>$this->marca,
                    'iva'=>$this->ivaDefecto,

                    'porcentaje'=> 0,
                    'precioLista'=> round($total,2) ,
                    'descuento'=> 0 ,

                    'costo'=> 0 ,
                    
                    'precio'=> round($total,2),
                    'cantidad'=>1,
                    'controlStock'=>'no',
                    'subtotal'=>  round($total,2),
    
                    ) ;
                $this->carrito['total']= round($total,2);
            }

           
            foreach ( $this->carrito['carrito'] as $key => $value) {
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
    function crearComprobanteM($total,$tipoDocumento,$cuit,){

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
            $tipo_de_documento = $tipoDocumento;

            /**
             * Numero de documento del comprador (0 para consumidor final)
             **/
            $numero_de_documento =$cuit;

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

           
            if( !isset($this->carrito['total'])){

                $this->carrito['carrito'][] = array(
                    'codigo'=>$this->codigo,
                    'detalle'=>$this->detalle,
                    'rubro'=>$this->rubro,
                    'proveedor'=>$this->proveedor,
                    'marca'=>$this->marca,
                    'iva'=>$this->ivaDefecto,

                    'porcentaje'=> 0,
                    'precioLista'=> round($total,2) ,
                    'descuento'=> 0 ,

                    'costo'=> 0 ,

                    'precio'=> round($total,2),
                    'cantidad'=>1,
                    'controlStock'=>'no',
                    'subtotal'=>  round($total,2),
    
                    ) ;
                $this->carrito['total']= round($total,2);
            }

           
            foreach ( $this->carrito['carrito'] as $key => $value) {
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

    function finalizarComprobante($nuevoComprobante,$descripcionTipoComp){

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
         

        
                // Post::create($validated);
                $comprobante = Comprobante::create([
                    
                    'numero' => $nuevoComprobante->FeDetResp->FECAEDetResponse->CbteDesde,
                    'total' => round($this->total,2),
                    'cae' => $nuevoComprobante->FeDetResp->FECAEDetResponse->CAE,
                    'fechaVencimiento' => $nuevoComprobante->FeDetResp->FECAEDetResponse->CAEFchVto,
                    'DocTipo'=>$nuevoComprobante->FeDetResp->FECAEDetResponse->DocTipo,
                    'cuitCliente' => $this->cuit,
                    'razonSocial'=>$this->razonSocial,
                    'tipoContribuyente'=>$this->tipoContribuyente,
                    'domicilio'=>$this->domicilio,
                    'empresa_id'=> $this->empresa->id,
                    'tipoComp'=>$this->tipoComprobante,
                    'fecha'=> Carbon::now()->format('Y-m-d H:i:s'),
                    'leyenda'=> $this->leyenda,

                    'idFormaPago'=>$this->idFormaPago,
                    'importeUno'=>$this->importeUno,
                    'idFormaPago2'=>($this->idFormaPago2 == 'NO')? $this->idFormaPago :  $this->idFormaPago2 ,
                    'importeDos'=>($this->idFormaPago2 == 'NO')? 0 :$this->importeDos ,


                    'ptoVta'=>$nuevoComprobante->FeCabResp->PtoVta,
                    'deposito_id'=>$this->usuario->deposito_id,
                    'usuario'=> $this->usuario->name,
                    'remito'=>  $this->remitoEntrega, //no (se entrega en el momento ) si (se entrega posterior)
                ]);

        
        
                $cliente = Cliente::updateOrCreate(
                    ['numeroDocumento'=>$this->cuit,'empresa_id'=> $this->empresa->id],
                    [            
                    'tipoDocumento'=>trim($this->tipoDocumento),
                    'numeroDocumento'=>trim($this->cuit),
                    'razonSocial'=>trim($this->razonSocial),
                    'domicilio'=>trim($this->domicilio),
                    'correo'=>trim($this->correoCliente),
                    'tipoContribuyente'=>trim($this->tipoContribuyente)]
                );
        
                // dd($this->carrito['carrito']);
        
                if(isset($this->carrito['carrito'])){//SI EXITE GUARDA LOS ARTICULOS
                    
                    foreach ($this->carrito['carrito'] as $key => $value) {
                        // array:6 [▼ // app/Livewire/Factura/NuevoComprobante.php:75
                        // "codigo" => "28464334"
                        // "detalle" => "alfajor"
                        // "precio" => 1
                        // "iva" => 21
                        // "cantidad" => 1
                        // "subtotal" => 1
                        // ]
                        // dump($value);
                        productoComprobante::create([
                            'comprobante_id'=> $comprobante->id,
                            'comprobante_numero'=>$comprobante->numero,
                            'codigo'=>$value['codigo'],
                            'detalle'=>$value['detalle'],

                            'porcentaje'=> $value['porcentaje'],
                            'precioLista'=> $value['precioLista'] ,
                            'descuento'=> $value['descuento'] ,
                            'costo'=> $value['costo'] ,



                            'precio'=>round($value['precio'] * $value['cantidad'],2),
                            'iva'=>$value['iva'],
                            'cantidad'=>$value['cantidad'],
                            'rubro'=>$value['rubro'],
                            'proveedor'=>$value['proveedor'],
                            'marca'=>$value['marca'],

                            'controlStock'=>$value['controlStock'],
                            'tipoComp'=>$this->tipoComprobante,
                            'fecha'=>$this->fechaHoy,
                            'idFormaPago'=>$this->idFormaPago,
                            'idFormaPago2'=>($this->idFormaPago2 == 'NO')? $this->idFormaPago :  $this->idFormaPago2,

                            'ptoVta'=>$nuevoComprobante->FeCabResp->PtoVta,
                            'usuario'=> $this->usuario->name,
                            'empresa_id'=> $this->empresa->id,
                        ]);
        
                        if($this->remitoEntrega == 'no' AND $value['controlStock'] == 'si'){  //no (se entrega en el momento ) si (se entrega posterior)
                            //AK DESCUENTA EL STOCK
                            DescontarStockEvent::dispatch([
                                'codigo'=>$value['codigo'],
                                'detalle'=>$value['detalle'],
                                'deposito_id'=>$this->usuario->deposito_id,
                                'stock'=>($value['cantidad'] * -1),
                                'comentario'=>'Venta '.$descripcionTipoComp.' N-'.$comprobante->numero,
                                'usuario'=>$this->usuario->name,
                                'empresa_id'=>$this->empresa->id,
                
                            ]);
                        }else{
                            //AK  EL REMITO CUANDO SE GERENERE DESCUENTA EL STOCK
                        }
                        
                    }
                    
                }

                
                if($this->idFormaPago == 0){  //aplica el saldo a cuenta corriente
                    //AK APLICA EL SALDO AL CLIENTE
                    SaldoCuentaCorriente::dispatch([
                        'empresa_id'=>$this->empresa->id,
                        'cliente_id'=>$cliente->id,
                        'comprobante_id'=>$comprobante->id,
                        'tipo'=>'venta',
                        'comentario'=>'un comentario',
                        'debe'=>round($this->importeUno,2),
                        'haber'=>0,
                        'usuario'=>$this->usuario->name,
                        // 'saldo'=>round($this->total,2), el saldo se calcula en listener 
        
                    ]);
                }else{
                    //AK  DEBERIA AUMENTAR LA CAJA
                }

                return $comprobante->id;
    }

    function finalizarPresupuesto(){

        $descripcionTipoComp = 'Presupuesto';

        // Obtener el último registro
        $ultimoRegistro = Presupuesto::where('empresa_id',$this->empresa->id)->latest()->first();

        if ($ultimoRegistro) {
            $ultimoId = $ultimoRegistro->id + 1;
            // echo "El último ID es: " . $ultimoId;
        } else {
            // echo "No hay registros en la tabla.";
            $ultimoId =  1;
        }

            // Post::create($validated);
            $presupuestoGuardado = Presupuesto::create([
                
                'tipoComp'=>$this->tipoComprobante,
                'numero' => $ultimoId,
                'total' => round($this->total,2),
                
                'fechaVencimiento' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s') ,// vencimiento del presupuesto 7 dias 
                'DocTipo'=>$this->tipoDocumento,
                'cuitCliente' => $this->cuit,
                'razonSocial'=>$this->razonSocial,
                'tipoContribuyente'=>$this->tipoContribuyente,
                'domicilio'=>$this->domicilio,
                'empresa_id'=> $this->empresa->id,
                'fecha'=> Carbon::now()->format('Y-m-d H:i:s'),
                'leyenda'=> $this->leyenda,
                'idFormaPago'=>$this->idFormaPago,
                
                'deposito_id'=>$this->usuario->deposito_id,
                'usuario'=> $this->usuario->name,
            ]);


            $cliente = Cliente::updateOrCreate(
                ['numeroDocumento'=>$this->cuit,'empresa_id'=> $this->empresa->id],
                [            
                'tipoDocumento'=>trim($this->tipoDocumento),
                'numeroDocumento'=>trim($this->cuit),
                'razonSocial'=>trim($this->razonSocial),
                'domicilio'=>trim($this->domicilio),
                'correo'=>trim($this->correoCliente),
                'tipoContribuyente'=>trim($this->tipoContribuyente)]
            );

            // dd($this->carrito['carrito']);

            if(isset($this->carrito['carrito'])){//SI EXITE GUARDA LOS ARTICULOS
                
                foreach ($this->carrito['carrito'] as $key => $value) {
                    // array:6 [▼ // app/Livewire/Factura/NuevoComprobante.php:75
                    // "codigo" => "28464334"
                    // "detalle" => "alfajor"
                    // "precio" => 1
                    // "iva" => 21
                    // "cantidad" => 1
                    // "subtotal" => 1
                    // ]
                    // dump($value);
                    ProductoPresupuesto::create([
                        'presupuesto_id'=> $presupuestoGuardado->id,
                        'presupuesto_numero'=>$presupuestoGuardado->numero,
                        'codigo'=>$value['codigo'],
                        'detalle'=>$value['detalle'],

                        'porcentaje'=> $value['porcentaje'],
                        'precioLista'=> $value['precioLista'] ,
                        'descuento'=> $value['descuento'] ,
                        'precio'=>$value['precio'],

                        'costo'=>$value['costo'],


                        'iva'=>$value['iva'],
                        'cantidad'=>$value['cantidad'],
                        'rubro'=>$value['rubro'],
                        'proveedor'=>$value['proveedor'],
                        'marca'=>$value['marca'],

                        'controlStock'=>$value['controlStock'],
                        'tipoComp'=>$this->tipoComprobante,
                        'fecha'=>$presupuestoGuardado->fecha,
                        'idFormaPago'=>$this->idFormaPago,
                        
                        'usuario'=> $this->usuario->name,
                        'empresa_id'=> $this->empresa->id,
                    ]);


                    //UN PRESUPUESTO NO DESCUENTA STOCK
                }
            }

            return $presupuestoGuardado->id;
          
    }






}


