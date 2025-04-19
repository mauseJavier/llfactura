<?php

namespace App\Livewire\FacturacionEmpresas;

use Livewire\Component;

use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

    // use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Http;

use Barryvdh\DomPDF\Facade\Pdf; 

use Illuminate\Support\Facades\Storage; 



use App\Models\Comprobante;
use App\Models\Empresa;

use Carbon\Carbon;


class EstadoEmpresa extends Component
{
    use WithPagination;

    public $razonSocial='';
    public $fechaDesde ;

    public $datoBuscado;
    public $filtroPago='';


    public $ordenarPor='';

    public function mount(){

        $this->fechaDesde = Carbon::now()->format('Y-m-d');


    }


    public function ordenarActualizado(){

        $this->ordenarPor='updated_at';

    }

    public function exportarCSV(){


        $filename = 'empresas_facturacion_mes_actual.csv';

        // Abrir o crear el archivo CSV
        $handle = fopen($filename, 'w');

        // Agregar los encabezados al archivo CSV
        fputcsv($handle, ['ID Empresa', 'Nombre Empresa', 'Total Facturado','Vencimiento','Pago','Importe','Usuario','Comentario']);

        // Obtener todas las empresas
        $empresas = DB::table('empresas')->get()->toArray();

        // Recorrer cada empresa para calcular el total facturado del mes actual
        foreach ($empresas as $key => $empresa) {
            $totalFacturado = DB::table('comprobantes')
                ->where('empresa_id', $empresa->id)
                ->whereBetween('fecha', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->sum('total');

            $empresas[$key]->totalFacturado = $totalFacturado;
        }

        // Ordenar las empresas por total facturado de forma descendente
        usort($empresas, function($a, $b) {
            return $b->totalFacturado <=> $a->totalFacturado;
        });

        // Escribir los datos de la consulta en el archivo CSV
        foreach ($empresas as $empresa) {
            fputcsv($handle, [
                $empresa->id,
                $empresa->razonSocial,
                $empresa->totalFacturado,
                $empresa->vencimientoPago,
                $empresa->pagoServicio == 'si' ?  'SI' : 'NO',
                $empresa->pagoMes,
                $empresa->usuarioPago,
                $empresa->comentario,




            ]);
        }

        // Cerrar el archivo CSV
        fclose($handle);

        // Mensaje de confirmación
        return response()->download($filename)->deleteFileAfterSend(true);

    }


    public function modificarFechaVencimientoPago(Empresa $empresa,$fecha,$pagoMes,$comentario,$pagoServicio){


        // dd($pagoServicio);
        $empresa->vencimientoPago=$fecha;
        $empresa->pagoMes=$pagoMes;
        $empresa->comentario=trim($comentario);
        $empresa->usuarioPago=Auth()->user()->name;

        $empresa->pagoServicio= $pagoServicio;


        $empresa->save();

        // dd($empresa->telefono);

 
        $messajedeWA=
            
        '¡Hola '.$empresa->titular.'! 👋🏼

            De LLFactura.com, Queremos acercarte ru recibo de pago.
            
            Si tienes alguna pregunta o necesitas más información, no dudes en contactarnos.

            ¡Gracias por ser parte de LLFactura.com! 😊🙌.

            Te envio el recibo de pago de este mes.';


        $instanciaWS= env('instanciaWhatsappLLFactura');
        $apikey= env('apikeyLLFactura');


        $tokenTelegram = env('tokenTelegram');

        

        $response = Http::get('https://api.telegram.org/bot'.$tokenTelegram.'/sendMessage', [
            'chat_id' => '7622868304', //este soy yo
            'text' => 'Usuario: '.Auth()->user()->name.' Cobro empresa: '. $empresa->razonSocial . ' $'.$pagoMes. ' telefono: api.whatsapp.com/send?phone=549'.$empresa->telefono,
        ]);

        // dd($response->json($key = null, $default = null));

        if($pagoMes > 1 ){

            
            $response = Http::withHeaders([
                'apikey' => $apikey,
            ])->post('https://evo.llservicios.ar/message/sendText/'.$instanciaWS, [
                'number' => '549'.$empresa->telefono,
                'text' => $messajedeWA .' Importe: $'.$pagoMes,
    
            ]);
    
    
    
            if (Storage::disk('local')->exists( 'public/llfactura/logo.png')) {
                // ...
                $urlLogo = Storage::url( 'public/llfactura/logo.png');
                $pathLogo = Storage::path('public/llfactura/logo.png');
                // $url = Storage::get('public/'.$empresa->cuit.'/logo/logo.png');
                // return  asset($url);
                // return $path;
                
            }else{
                $urlLogo = '';
                $pathLogo ='';
            }
    
            $info=[
                'titulo'=>'Pago Servicio:'. $empresa->razonSocial,
                'cliente'=>$empresa,
                'fecha'=>Carbon::now()->format('d-m-Y'),
                'importe'=>number_format($pagoMes, 2, ',', '.'),
                'logo'=>$pathLogo,
            ];
    
            // dd($info);
    
            // resources/views/PDF/pdfReciboPagoServicioCliente.blade.php
            $pdf = Pdf::loadView('PDF.pdfReciboPagoServicioCliente',$info);
            // $pdf->set_paper(array(0,0,250,(300)), 'portrait');
            // $pdf->getCanvas()->page_text(15,800, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));
    
            $nombreArchivo= 'Pago Servicio:'. $empresa->razonSocial.' '.Carbon::now()->format('Ymd').'.pdf';
            // return $pdf->download($nombreArchivo);
            // return $pdf->stream($nombreArchivo);   
    
            // Obtener el contenido binario del PDF
            $pdfContent = $pdf->output();
            // return $pdf->stream($nombreArchivo);   
            // return $pdf->download($nombreArchivo);
    
    
    
            // Convertir a Base64
            $pdfBase64 = base64_encode($pdfContent);
    
            // dd($pdfBase64);
            
    
            $response = Http::withHeaders([
                'apikey' => $apikey,
                'Content-Type'=> 'application/json',
    
            ])->post('https://evo.llservicios.ar/message/sendMedia/'.$instanciaWS, [ //https://localhost:8080/message/sendMedia/dfgdfg '{"media":"dsgergdfgdfg","mediatype":"image","number":"33333"}'
                'number' => '549'.$empresa->telefono,
                'media'=>$pdfBase64,
                'mediatype'=>'document',//image
                'fileName'=>'Pago Servicio:'. $empresa->razonSocial .'.pdf',//
    
    
            ]);
    
            session()->flash('mensaje', 'Fecha Actualizada. '. $empresa->razonSocial .' Fecha: '. $fecha .':: '. $response->body());


        }else{

            session()->flash('mensaje', 'Fecha Actualizada. '. $empresa->razonSocial .' Fecha: '. $fecha .':: SIN ENVIO WHATSAPP ');
        }



    }

    public function pagarEmpresa(Empresa $empresa,$fecha){



        $empresa->vencimientoPago="$fecha";
        $empresa->pagoServicio= $empresa->pagoServicio == 'si' ?  'no' : 'si';

        $empresa->save();

        $this->render();

        session()->flash('mensaje', 'Pago Realizado. '. $empresa->razonSocial .' Fecha: '. $fecha);


    }


    public function reiniciarPagos(){

        // Crea una instancia de Carbon para la fecha actual
            $fechaActual = Carbon::now();

            // Modifica el día a 10
            $dia10 = $fechaActual->setDay(10);

            // Formatea la fecha al formato deseado: '2024-11-01'
            $fechaFormateada = $dia10->format('Y-m-d');

            $affected = DB::table('empresas')
              ->update(['vencimientoPago' => $fechaFormateada,
              'pagoServicio'=>'no',
              'usuarioPago'=>'',
              'pagoMes'=>0,


            ]);


                session()->flash('mensaje', 'Riniciado mes actual. '. $affected);


    }




    public function render()
    {
        $inicioMes = Carbon::now()->startOfMonth()->toDateString();
        $finMes = Carbon::now()->endOfMonth()->toDateString();
    
        // --- Construcción de consulta base con filtros ---
        $baseQuery = DB::table('empresas')
            ->when($this->datoBuscado, function ($query) {
                $search = '%' . $this->datoBuscado . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('empresas.razonSocial', 'like', $search)
                      ->orWhere('empresas.titular', 'like', $search)
                      ->orWhere('empresas.cuit', 'like', $search);
                });
            })
            ->when($this->filtroPago, function ($query) {
                $query->where('pagoServicio', $this->filtroPago);
            });
    
            // --- Consulta principal con LEFT JOIN y agrupación para total facturado ---
            $empresas = $baseQuery
            ->leftJoin('comprobantes', function ($join) use ($inicioMes, $finMes) {
                $join->on('empresas.id', '=', 'comprobantes.empresa_id')
                     ->whereBetween('comprobantes.fecha', [$inicioMes, $finMes]);
            })
            ->select(
                'empresas.id',
                'empresas.razonSocial',
                'empresas.titular',
                'empresas.cuit',
                'empresas.pagoServicio',
                'empresas.updated_at',
                'empresas.usuarioPago',
                'empresas.vencimientoPago',
                'empresas.pagoMes',
                'empresas.comentario',
                'empresas.telefono',
                DB::raw('COALESCE(SUM(comprobantes.total), 0) as totalFacturado')
            )
            ->groupBy(
                'empresas.id',
                'empresas.razonSocial',
                'empresas.titular',
                'empresas.cuit',
                'empresas.pagoServicio',
                'empresas.updated_at',
                'empresas.vencimientoPago',
                'empresas.pagoMes',
                'empresas.comentario',
                'empresas.telefono',
                'empresas.usuarioPago'
            )
            ->when($this->ordenarPor === 'updated_at', function ($query) {
                $query->orderByDesc('empresas.updated_at');
            }, function ($query) {
                $query->orderByDesc(DB::raw('COALESCE(SUM(comprobantes.total), 0)'));
            })
            ->get();
        
    
    
            // --- Total por usuario ---
            $totalPorUsuario = DB::table('empresas')
            ->when($this->datoBuscado, function ($query) {
                $search = '%' . $this->datoBuscado . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('razonSocial', 'like', $search)
                      ->orWhere('titular', 'like', $search)
                      ->orWhere('cuit', 'like', $search);
                });
            })
            ->when($this->filtroPago, function ($query) {
                $query->where('pagoServicio', $this->filtroPago);
            })
            ->select(
                DB::raw("IFNULL(usuarioPago, 'Sin usuario') as usuario"),
                DB::raw("SUM(COALESCE(pagoMes, 0)) as totalPagos"),
                DB::raw("COUNT(*) as cantidad")
            )
            ->groupBy(DB::raw("IFNULL(usuarioPago, 'Sin usuario')"))
            ->get();
        

            // dd($totalPorUsuario);
    

    
            // --- Total general ---
            $total = DB::table('empresas')
            ->when($this->datoBuscado, function ($query) {
                $search = '%' . $this->datoBuscado . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('razonSocial', 'like', $search)
                    ->orWhere('titular', 'like', $search)
                    ->orWhere('cuit', 'like', $search);
                });
            })
            ->when($this->filtroPago, function ($query) {
                $query->where('pagoServicio', $this->filtroPago);
            })
            ->whereBetween('vencimientoPago', [
                Carbon::now()->startOfMonth()->toDateString(),
                Carbon::now()->endOfMonth()->toDateString()
            ])
            ->select(DB::raw('SUM(COALESCE(pagoMes, 0)) as total'),
                DB::raw('COUNT(*) as totalCantidad')
            )
            ->first();
    
        
        return view('livewire.facturacion-empresas.estado-empresa', [
            'empresas' => $empresas,
            'totalPorUsuario' => $totalPorUsuario,
            'total' => $total,
        ])
        ->extends('layouts.app')
        ->section('main');
    }
    
}
