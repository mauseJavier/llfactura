<?php

namespace App\Livewire\Comprobante;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;




use Carbon\Carbon;

use Livewire\WithPagination;

use Livewire\Component;

use App\Models\Comprobante;
use App\Models\productoComprobante;

use App\Models\Empresa;


class VerComprobante extends Component
{
    use WithPagination;

    public $tiposComprobantes;

    public $fechaFiltroDesde;
    public $fechaFiltroHasta;

    public $tipoComp;
    public $usuarioFiltro;
    public $numeroComprobanteFiltro;
    public $clienteComprobanteFiltro;


    public function limpiarFiltro(){

        $this->tipoComp = '';
        $this->usuarioFiltro = '';
        $this->numeroComprobanteFiltro="";
    }

    public function modificarFechas($dato){

        
        if($dato != ''){

            $this->tipoComp='';
            $this->usuarioFiltro='';
            $this->numeroComprobanteFiltro='';
            $this->clienteComprobanteFiltro='';
            
            if(is_numeric($dato)){

                $this->numeroComprobanteFiltro = $dato;

            }else
            {
                $this->clienteComprobanteFiltro = $dato;
            }
            $this->fechaFiltroDesde = '2020-01-01';
        
            $this->fechaFiltroHasta =  '2050-01-01';

        }else{

            $this->fechaFiltroDesde = Carbon::now();
            $this->fechaFiltroDesde->setTime(0, 0);
            $this->fechaFiltroDesde = $this->fechaFiltroDesde->format('Y-m-d\TH:i');
            
            $this->fechaFiltroHasta = Carbon::now()->addDay()->format('Y-m-d\TH:i');

        }
    }

    public function agregarDia(){

        // Convierte la fecha a una instancia de Carbon
        $carbonFecha = Carbon::parse($this->fechaFiltroDesde);

        // Agrega un día
        $carbonFecha->addDay();

        // Actualiza la propiedad con la nueva fecha
        $this->fechaFiltroDesde = $carbonFecha->format('Y-m-d\TH:i');

        // Convierte la fecha a una instancia de Carbon
        $carbonFecha = Carbon::parse($this->fechaFiltroHasta);

        // Agrega un día
        $carbonFecha->addDay();

        // Actualiza la propiedad con la nueva fecha
        $this->fechaFiltroHasta = $carbonFecha->format('Y-m-d\TH:i');


    }

    public function restarDia(){

        // Convierte la fecha a una instancia de Carbon
        $carbonFecha = Carbon::parse($this->fechaFiltroDesde);

        // Agrega un día
        $carbonFecha->subDay();

        // Actualiza la propiedad con la nueva fecha
        $this->fechaFiltroDesde = $carbonFecha->format('Y-m-d\TH:i');

        // Convierte la fecha a una instancia de Carbon
        $carbonFecha = Carbon::parse($this->fechaFiltroHasta);

        // Agrega un día
        $carbonFecha->subDay();

        // Actualiza la propiedad con la nueva fecha
        $this->fechaFiltroHasta = $carbonFecha->format('Y-m-d\TH:i');;


    }


    public function mount(){

        // $this->fechaFiltroDesde = Carbon::now();
        // $this->fechaFiltroDesde->setTime(0, 0);
        // $this->fechaFiltroDesde = $this->fechaFiltroDesde->format('Y-m-d\TH:i');
        
        // $this->fechaFiltroHasta = Carbon::now()->addDay()->format('Y-m-d\TH:i');

        // Comienzo del día actual
        $comienzoDelDia = Carbon::now()->startOfDay()->format('Y-m-d\TH:i');

        // Última hora del día actual
        $ultimaHoraDelDia = Carbon::now()->endOfDay()->format('Y-m-d\TH:i');

        $this->fechaFiltroDesde = $comienzoDelDia;
        
        $this->fechaFiltroHasta = $ultimaHoraDelDia;

        $this->tipoComp = '';
        $this->usuarioFiltro = '';
        $this->numeroComprobanteFiltro="";


        $monotributo = [

            '11'=>'Factura C',

            'remito'=>'Remito',

            '13'=>'NC C',
            'notaRemito'=>'NC R',


        ];

        $responsable = [
            '1'=>'Factura A',
            '6'=>'Factura B',
            '51'=>'Factura M',
            'remito'=>'Remito',

            '3'=>'NC A',
            '8'=>'NC B',


            'notaRemito'=>'NC R',


        ];


        // $array2=array();

        // foreach (Comprobante::select('tipoComp')->distinct()->where('empresa_id', Auth::user()->empresa_id)->get() as $key => $value) {        
        //     array_push($array2, $value->tipoComp);
        // }


        // // Usamos array_intersect_key con array_flip para intersectar usando claves
        // $this->tiposComprobantes = array_intersect_key($array1, array_flip($array2));

        $empresa =(Empresa::find(Auth()->user()->empresa_id));

        // dd($empresa->iva);

        if($empresa->iva == 'ME'){
            $this->tiposComprobantes = $monotributo;

        }else{
            $this->tiposComprobantes = $responsable;

        }

        $this->usuarioFiltro = Auth::user()->name;




    }

    // Método para actualizar las fechas desde Alpine.js
    public function actualizarFechas($fechaDesde, $fechaHasta)
    {
        $this->fechaFiltroDesde = $fechaDesde;
        $this->fechaFiltroHasta = $fechaHasta;
    }

    public function exportarCSV(){


        // ak ahy que agregar las dos formas de pago 

        $filename = 'comprobantes'.Carbon::now().'.csv';

        // Abrir o crear el archivo CSV
        $handle = fopen($filename, 'w');

        // "id" => 15650
        // "tipoComp" => "11"
        // "numero" => 2080
        // "total" => 14560.0
        // "cae" => 74432202688187
        // "fechaVencimiento" => "2024-11-03"
        // "fecha" => "2024-10-24 19:56:13"
        // "ptoVta" => 1
        // "deposito_id" => 1
        // "DocTipo" => 99
        // "cuitCliente" => 0
        // "razonSocial" => "Consumidor Final"
        // "tipoContribuyente" => 5
        // "domicilio" => null
        // "leyenda" => null
        // "idFormaPago" => 5
        // "remito" => "no"
        // "empresa_id" => 1
        // "usuario" => "JAVIER LLFACTURA"
        // "created_at" => "2024-10-24 19:56:13"
        // "updated_at" => "2024-10-24 19:56:13"

        // Agregar los encabezados al archivo CSV
        fputcsv($handle, ['ID','Fecha', 'tipoComp', 'Numero','Total','CAE','Ven.CAE','PtoVenta',
                            'TipoDoc','NumDoc','RazonSocial','Domicilio','Leyenda','FPagoUno','ImporteUno','FPagoDos','ImporteDos','Usuario']);

        // Ejecutar la consulta para obtener los datos
        $comprobantes = Comprobante::select(
            'comprobantes.*',
            'fp1.nombre as nombreFormaPago1',
            'fp2.nombre as nombreFormaPago2'
        )
        ->join('forma_pagos as fp1', 'comprobantes.idFormaPago', '=', 'fp1.id')
        ->join('forma_pagos as fp2', 'comprobantes.idFormaPago2', '=', 'fp2.id')
        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
        ->where('comprobantes.created_at', '>=', $this->fechaFiltroDesde)
        ->where('comprobantes.created_at', '<=', $this->fechaFiltroHasta)
        ->when($this->tipoComp, function ($query, $tipoComp) {
            return $query->where('comprobantes.tipoComp', $tipoComp);
        })
        ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
            return $query->where('comprobantes.numero', '=', $numeroComprobanteFiltro);
        })
        ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
            return $query->where('comprobantes.razonSocial', 'LIKE', '%' . $clienteComprobanteFiltro . '%');
        })
        ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
        ->orderByDesc('comprobantes.created_at')
        ->get();

            // dd($comprobantes);

        // Escribir los datos de la consulta en el archivo CSV
        foreach ($comprobantes as $item) {

            switch ($item->tipoComp) {
                case 1:
                $tipoComp = 'Fac A';
                break;
                case 6:
                $tipoComp = 'Fac B';
                break;
                case 11:
                $tipoComp = 'Fac C';
                break;
                case 51:
                $tipoComp = 'Fac M';
                break;
                case 'remito':
                $tipoComp = 'Remito';
                break;
                case 3:
                $tipoComp = 'NC A';
                break;
                case 8:
                $tipoComp = 'NC B';
                break;
                case 13:
                $tipoComp = 'NC C';
                break;
                case 'notaRemito':
                $tipoComp = 'NC R';
                break;
                
                default:
                    # code...
                    break;
            }
            fputcsv($handle, [
                $item->id,
                $item->fecha,
                $tipoComp,
                $item->numero,
                str_replace('.',',',$item->total),
                $item->cae,
                $item->fechaVencimiento,
                $item->ptoVta,
                $item->DocTipo,
                $item->cuitCliente,
                $item->razonSocial,
                $item->domicilio,
                $item->leyenda,
                $item->nombreFormaPago1,
                $item->importeUno,             
                
                $item->nombreFormaPago2,
                $item->importeDos,

                $item->usuario,

            ]);
        }

        // Cerrar el archivo CSV
        fclose($handle);

        // Mensaje de confirmación
        return response()->download($filename)->deleteFileAfterSend(true);

    }


    public function crearPDF(){


        // ak ahy que agregar el nuevo totoales 
        

        $fechas= array('fdesde'=>date('d/m/y',strtotime($this->fechaFiltroDesde)),'fhasta'=>date('d/m/y',strtotime($this->fechaFiltroHasta)));

        $collection = Comprobante::select('comprobantes.idFormaPago as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporte'))
        ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
        ->whereBetween('comprobantes.created_at', [$this->fechaFiltroDesde, $this->fechaFiltroHasta])
        ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
        ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
        ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
        ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
        ->groupBy('comprobantes.idFormaPago', 'forma_pagos.nombre')
        
        // Unir la segunda colección
        ->unionAll(
            Comprobante::select('comprobantes.idFormaPago2 as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeDos) as totalImporte'))
                ->join('forma_pagos', 'comprobantes.idFormaPago2', '=', 'forma_pagos.id')
                ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                ->whereBetween('comprobantes.created_at', [$this->fechaFiltroDesde, $this->fechaFiltroHasta])
                ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
                ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
                ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
                ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
                ->groupBy('comprobantes.idFormaPago2', 'forma_pagos.nombre')
        )
        ->get();

            // Procesar los resultados combinados en un único arreglo de totales
            $totales = [];

            foreach ($collection as $comprobante) {
                $idFormaPago = $comprobante->idFormaPago;
                $nombre = $comprobante->nombre;
                $totalImporte = $comprobante->totalImporte;

                if (!isset($totales[$idFormaPago])) {
                    $totales[$idFormaPago] = [
                        'nombre' => $nombre,
                        'total' => 0,
                    ];
                }

                $totales[$idFormaPago]['total'] += $totalImporte;
            }

            // dd($totales);

        // Ejecutar la consulta para obtener los datos
        $comprobantes = Comprobante::where('empresa_id', Auth::user()->empresa_id)
        ->where('created_at', '>=', $this->fechaFiltroDesde)
        ->where('created_at', '<=', $this->fechaFiltroHasta)
        ->when($this->tipoComp, function ($query, $tipoComp) {
            return $query->where('tipoComp', $tipoComp);
        })
        ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
        return $query->where('numero', '=',$numeroComprobanteFiltro);
        })

        ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
        return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
        })
        ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
        ->orderByDesc('created_at')
        ->get();


        $sumComprobantes = Comprobante::select('tipoComp', DB::raw('SUM(total) as sumTotal'))
        ->where('empresa_id', Auth::user()->empresa_id)
        ->where('created_at', '>=', $this->fechaFiltroDesde)
        ->where('created_at', '<=', $this->fechaFiltroHasta)
        ->when($this->tipoComp, function ($query, $tipoComp) {
        return $query->where('tipoComp', $tipoComp);
        })
        ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
        return $query->where('numero', '=',$numeroComprobanteFiltro);
        })
        ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
        return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
        })
        ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
        ->groupBy('tipoComp')
        ->get();



            $sumTotal = Comprobante::where('empresa_id', Auth::user()->empresa_id)
            ->where('created_at', '>=', $this->fechaFiltroDesde)
            ->where('created_at', '<=', $this->fechaFiltroHasta)
            ->when($this->tipoComp, function ($query, $tipoComp) {
            return $query->where('tipoComp', $tipoComp);
            })
            ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
            return $query->where('numero', '=',$numeroComprobanteFiltro);
            })
            ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
            return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
            })
            ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
            ->sum('total');


        // Nombre del archivo
        $nombreArchivo = 'comprobantes'.Carbon::now().'.pdf';

        $pdf = Pdf::loadView('PDF.pdfReporteComprobantes',compact('fechas','comprobantes','sumComprobantes','totales','sumTotal'));    

        // Guardar el PDF en el almacenamiento en 'public'
        $rutaArchivo = 'public/pdf/' . $nombreArchivo;
        Storage::put($rutaArchivo, $pdf->output());

        // Generar la URL de descarga
        $rutaDescarga = Storage::path($rutaArchivo);

        // dd($rutaDescarga);

        // Crear la respuesta para la descarga
        return Response::download($rutaDescarga)->deleteFileAfterSend(true);


    }
    
    public function render()
    {


            $collection = Comprobante::select('comprobantes.idFormaPago as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporte'))
                ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
                ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                ->whereBetween('comprobantes.created_at', [$this->fechaFiltroDesde, $this->fechaFiltroHasta])
                ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
                ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
                ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
                ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
                ->groupBy('comprobantes.idFormaPago', 'forma_pagos.nombre')
                
                // Unir la segunda colección
                ->unionAll(
                    Comprobante::select('comprobantes.idFormaPago2 as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeDos) as totalImporte'))
                        ->join('forma_pagos', 'comprobantes.idFormaPago2', '=', 'forma_pagos.id')
                        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                        ->whereBetween('comprobantes.created_at', [$this->fechaFiltroDesde, $this->fechaFiltroHasta])
                        ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
                        ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
                        ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
                        ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
                        ->groupBy('comprobantes.idFormaPago2', 'forma_pagos.nombre')
                )
                ->get();

            // Procesar los resultados combinados en un único arreglo de totales
            $totales = [];

            foreach ($collection as $comprobante) {
                $idFormaPago = $comprobante->idFormaPago;
                $nombre = $comprobante->nombre;
                $totalImporte = $comprobante->totalImporte;

                if (!isset($totales[$idFormaPago])) {
                    $totales[$idFormaPago] = [
                        'nombre' => $nombre,
                        'total' => 0,
                    ];
                }

                $totales[$idFormaPago]['total'] += $totalImporte;
            }




            $ivaPeriodo = productoComprobante::where('empresa_id', Auth::user()->empresa_id)
            ->where('fecha', '>=', $this->fechaFiltroDesde)
            ->where('fecha', '<=', $this->fechaFiltroHasta)
            ->whereNotIn('tipoComp', ['remito', 'notaRemito',]) // Excluir múltiples valores
            ->get()
            ->map(function ($item) {

                if($item->tipoComp == 3 OR $item->tipoComp == 8 OR $item->tipoComp == 13 ){


                    // Calcular el precio con IVA incluido
                    $item->precioConIva = round( ($item->precio * $item->cantidad) - (($item->precio * $item->cantidad) / (1 + ($item->iva / 100))) ,2) * -1;

                }else{

                    // Calcular el precio con IVA incluido
                    $item->precioConIva = round( ($item->precio * $item->cantidad) - (($item->precio * $item->cantidad) / (1 + ($item->iva / 100))),2);
                }
                // $item->precioConIva = round( ($item->precio * $item->cantidad) ,2);

                return $item;
            });

            $sumaTotalConIva = $ivaPeriodo->sum('precioConIva'); // Sumar todos los precios con IVA incluido
        
        
            
            // $ivaIncluido = $precio - ($precio / (1 + ($ivaPorcentaje / 100)));


        return view('livewire.comprobante.ver-comprobante',[

            'ivaPeriodo'=> $sumaTotalConIva,

            'comprobantes' => Comprobante::where('empresa_id', Auth::user()->empresa_id)
                                ->where('created_at', '>=', $this->fechaFiltroDesde)
                                ->where('created_at', '<=', $this->fechaFiltroHasta)
                                ->when($this->tipoComp, function ($query, $tipoComp) {
                                    return $query->where('tipoComp', $tipoComp);
                                })
                                ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
                                return $query->where('numero', '=',$numeroComprobanteFiltro);
                                })

                                ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
                                return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
                                })
                                ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
                                ->orderByDesc('created_at')
                                ->paginate(15),

            'sumTotal' => Comprobante::where('empresa_id', Auth::user()->empresa_id)
                                        ->where('created_at', '>=', $this->fechaFiltroDesde)
                                        ->where('created_at', '<=', $this->fechaFiltroHasta)
                                        ->when($this->tipoComp, function ($query, $tipoComp) {
                                            return $query->where('tipoComp', $tipoComp);
                                        })
                                ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
                                return $query->where('numero', '=',$numeroComprobanteFiltro);
                                })

                                ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
                                return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
                                })


                                        ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
                                        ->sum('total'),

            'sumComprobantes' => Comprobante::select('tipoComp', DB::raw('SUM(total) as sumTotal'))
                                        ->where('empresa_id', Auth::user()->empresa_id)
                                        ->where('created_at', '>=', $this->fechaFiltroDesde)
                                        ->where('created_at', '<=', $this->fechaFiltroHasta)
                                        ->when($this->tipoComp, function ($query, $tipoComp) {
                                            return $query->where('tipoComp', $tipoComp);
                                        })
                                ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
                                return $query->where('numero', '=',$numeroComprobanteFiltro);
                                })

                                ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
                                return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
                                })


                                        ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
                                        ->groupBy('tipoComp')
                                        ->get(),

            'totales'=>$totales,
            
        ])        
        ->extends('layouts.app')
        ->section('main');
    }
}



  //CONSULTA VIEJA PARA LAS DOS FORMAS DE PAGO Y LOS TOTALES 

        // $collectionFormaPago1 = Comprobante::select('comprobantes.idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporteUno'))
        // ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')

        // ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
        // ->where('comprobantes.created_at', '>=', $this->fechaFiltroDesde)
        // ->where('comprobantes.created_at', '<=', $this->fechaFiltroHasta)
        // ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
        // ->when($this->tipoComp, function ($query, $tipoComp) {
        // return $query->where('comprobantes.tipoComp', $tipoComp);
        // })
        // ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
        // return $query->where('numero', '=',$numeroComprobanteFiltro);
        // })

        // ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
        // return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
        // })
        // ->groupBy('comprobantes.idFormaPago','forma_pagos.nombre')
        // ->get();

        // $collectionFormaPago2 = Comprobante::select('comprobantes.idFormaPago2', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeDos) as totalImporteDos'))
        // ->join('forma_pagos', 'comprobantes.idFormaPago2', '=', 'forma_pagos.id')

        // ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
        // ->where('comprobantes.created_at', '>=', $this->fechaFiltroDesde)
        // ->where('comprobantes.created_at', '<=', $this->fechaFiltroHasta)
        // ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
        // ->when($this->tipoComp, function ($query, $tipoComp) {
        // return $query->where('comprobantes.tipoComp', $tipoComp);
        // })
        // ->when($this->numeroComprobanteFiltro, function ($query, $numeroComprobanteFiltro) {
        // return $query->where('numero', '=',$numeroComprobanteFiltro);
        // })

        // ->when($this->clienteComprobanteFiltro, function ($query, $clienteComprobanteFiltro) {
        // return $query->where('razonSocial', 'LIKE','%'.$clienteComprobanteFiltro.'%');
        // })
        // ->groupBy('comprobantes.idFormaPago2','forma_pagos.nombre')
        // ->get();



        //     $totales = [];

        //     // Sumar totales para idFormaPago (importeUno)
        //     foreach ($collectionFormaPago1 as $comprobante) {
        //         $idFormaPago = $comprobante->idFormaPago;
        //         $nombre = $comprobante->nombre;
        //         $totalImporteUno = $comprobante->totalImporteUno;

        //         if (!isset($totales[$idFormaPago])) {
        //             $totales[$idFormaPago] = [
        //                 'nombre' => $nombre,
        //                 'total' => 0,
        //             ];
        //         }

        //         $totales[$idFormaPago]['total'] += $totalImporteUno;
        //     }

        //     // Sumar totales para idFormaPago2 (importeDos)
        //     foreach ($collectionFormaPago2 as $comprobante) {
        //         $idFormaPago2 = $comprobante->idFormaPago2;
        //         $nombre = $comprobante->nombre;
        //         $totalImporteDos = $comprobante->totalImporteDos;

        //         if (!isset($totales[$idFormaPago2])) {
        //             $totales[$idFormaPago2] = [
        //                 'nombre' => $nombre,
        //                 'total' => 0,
       
        //             ];
        //         }

        //         $totales[$idFormaPago2]['total'] += $totalImporteDos;
        //     }


