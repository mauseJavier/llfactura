<?php

namespace App\Livewire\Ventas;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

use Livewire\WithPagination;

// use App\Models\User;
// use App\Models\Empresa;
use App\Models\productoComprobante;


class VerVentasArticulos extends Component
{
    use WithPagination;

    public $fechaDesde,$fechaHasta, $precioVenta, $costoVenta,$resultadoVenta,$resultadoPorcentaje,
    $codigo,
    $detalle,
    $rubro,
    $proveedor,
    $marca,
    $colorRojo='red';


    public function mount(){

        $this->fechaDesde = Carbon::now();
        // $this->fechaDesde->setTime(0, 0);
        $this->fechaDesde = $this->fechaDesde->format('Y-m-d');
        
        $this->fechaHasta = Carbon::now()->addDay()->format('Y-m-d');

        $this->calcularVenta();
    }

    public function calcularVenta(){

        $fechaInicio= $this->fechaDesde;
        $fechaFin= $this->fechaHasta;

        $codigo = $this->codigo;
        $detalle = $this->detalle;
        $rubro = $this->rubro;
        $proveedor = $this->proveedor;
        $marca = $this->marca;


        $res = DB::table('producto_comprobantes')
        ->select(DB::raw('
            SUM(CASE 
                WHEN tipoComp IN (3, 8, 13, "notaRemito") THEN -precio * cantidad
                ELSE precio * cantidad
            END) as total_precio,
            SUM(CASE 
                WHEN tipoComp IN (3, 8, 13, "notaRemito") THEN -costo * cantidad
                ELSE costo * cantidad
            END) as total_costo
        '))
        ->where('empresa_id', Auth::user()->empresa_id)
        ->whereBetween('fecha', [$this->fechaDesde, $this->fechaHasta])
        ->when($codigo, function ($query) use ($codigo) {
            return $query->where('codigo', 'like', '%' . $codigo . '%');
        })
        ->when($detalle, function ($query) use ($detalle) {
            return $query->where('detalle', 'like', '%' . $detalle . '%');
        })
        ->when($rubro, function ($query) use ($rubro) {
            return $query->where('rubro', 'like', '%' . $rubro . '%');
        })
        ->when($proveedor, function ($query) use ($proveedor) {
            return $query->where('proveedor', 'like', '%' . $proveedor . '%');
        })
        ->when($marca, function ($query) use ($marca) {
            return $query->where('marca', 'like', '%' . $marca . '%');
        })
        ->first();
    

            // dd($res);

            
            $this->precioVenta = $res->total_precio ?  number_format($res->total_precio, 2, ',', '.') :  number_format(0, 2, ',', '.');
            $this->costoVenta = $res->total_costo ?  number_format($res->total_costo, 2, ',', '.') :  number_format(0, 2, ',', '.');

            $dif =$res->total_precio - $res->total_costo;
            $this->resultadoVenta = number_format($dif, 2, ',', '.');
            $por = $res->total_costo > 0 ? ($dif * 100)/$res->total_costo : 0;
            $this->resultadoPorcentaje = number_format($por, 2, ',', '.');

    }


    public function exportarCSV(){

        $codigo = $this->codigo;
        $detalle = $this->detalle;
        $rubro = $this->rubro;
        $proveedor = $this->proveedor;
        $marca = $this->marca;


        $filename = 'ventaXarticulos'.Carbon::now().'.csv';

        // Abrir o crear el archivo CSV
        $handle = fopen($filename, 'w');

        // "id" => 5
        // "comprobante_id" => 5
        // "comprobante_numero" => 129
        // "codigo" => "4631658036149"
        // "detalle" => "Vodka"
        // "porcentaje" => 0.0
        // "precioLista" => 35.7
        // "descuento" => 0.0
        // "precio" => 35.7
        // "iva" => 10.5
        // "cantidad" => 1.0
        // "costo" => 0.0
        // "rubro" => "Electrónica"
        // "proveedor" => "MAUSE"
        // "marca" => "General"
        // "controlStock" => "si"
        // "fecha" => "2024-06-24"
        // "tipoComp" => "11"
        // "idFormaPago" => 1
        // "ptoVta" => 4
        // "usuario" => "MAUSE LLFACTURA"
        // "empresa_id" => 1

        // Agregar los encabezados al archivo CSV
        fputcsv($handle, ['Fecha','Codigo','Detalle', 'Cantidad', 
                            'Costo','PLista','Descuento',
                            'PVenta','Rubro',
                            'Proveedor','Marca',
                            'TipoComp','PtoVenta','Usuario',]);

        // Ejecutar la consulta para obtener los datos
        $articulos= productoComprobante::where('empresa_id',Auth::user()->empresa_id)
        ->whereBetween('fecha', [$this->fechaDesde, $this->fechaHasta])

        ->when($codigo, function ($query) use ($codigo) {
            return $query->where('codigo', 'like', '%' . $codigo . '%');
        })
        ->when($detalle, function ($query) use ($detalle) {
            return $query->where('detalle', 'like', '%' . $detalle . '%');
        })
        ->when($rubro, function ($query) use ($rubro) {
            return $query->where('rubro', 'like', '%' . $rubro . '%');
        })
        ->when($proveedor, function ($query) use ($proveedor) {
            return $query->where('proveedor', 'like', '%' . $proveedor . '%');
        })
        ->when($marca, function ($query) use ($marca) {
            return $query->where('marca', 'like', '%' . $marca . '%');
        })        
        ->get();

            // dd($articulos);

        // Escribir los datos de la consulta en el archivo CSV
        foreach ($articulos as $item) {

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
                $item->fecha,
                $item->codigo,
                $item->detalle,
                $item->cantidad,
                str_replace('.',',',$item->costo),
                str_replace('.',',',$item->precioLista),
                str_replace('.',',',$item->descuento),
                str_replace('.',',',$item->precio),



                $item->rubro,
                $item->proveedor,
                $item->marca,
                $tipoComp,
                $item->ptoVta,
                $item->usuario,



            ]);
        }

        // Cerrar el archivo CSV
        fclose($handle);

        // Mensaje de confirmación
        return response()->download($filename)->deleteFileAfterSend(true);

    }


    public function crearPDF(){


        $codigo = $this->codigo;
        $detalle = $this->detalle;
        $rubro = $this->rubro;
        $proveedor = $this->proveedor;
        $marca = $this->marca;

        // $fechas= array('fdesde'=>date('d/m/y',strtotime($this->fechaFiltroDesde)),'fhasta'=>date('d/m/y',strtotime($this->fechaFiltroHasta)));

        $fechas= array('fdesde'=>$this->fechaDesde,'fhasta'=>$this->fechaHasta);
        $totales=array('precioVenta'=>$this->precioVenta,
                        'costoVenta'=>$this->costoVenta,
                        'resultadoVenta'=>$this->resultadoVenta,
                        'resultadoPorcentaje'=>$this->resultadoPorcentaje);


        // Ejecutar la consulta para obtener los datos
        $articulos= productoComprobante::where('empresa_id',Auth::user()->empresa_id)
        ->whereBetween('fecha', [$this->fechaDesde, $this->fechaHasta])

        ->when($codigo, function ($query) use ($codigo) {
            return $query->where('codigo', 'like', '%' . $codigo . '%');
        })
        ->when($detalle, function ($query) use ($detalle) {
            return $query->where('detalle', 'like', '%' . $detalle . '%');
        })
        ->when($rubro, function ($query) use ($rubro) {
            return $query->where('rubro', 'like', '%' . $rubro . '%');
        })
        ->when($proveedor, function ($query) use ($proveedor) {
            return $query->where('proveedor', 'like', '%' . $proveedor . '%');
        })
        ->when($marca, function ($query) use ($marca) {
            return $query->where('marca', 'like', '%' . $marca . '%');
        })
        
        ->get();

        $iva= productoComprobante::                        
        select(DB::raw('SUM(round( precio - (precio / (iva / 100 + 1)), 3)) as totalSINiva'))
        ->where('empresa_id',Auth::user()->empresa_id)
        ->whereBetween('fecha', [$this->fechaDesde, $this->fechaHasta])

        ->when($codigo, function ($query) use ($codigo) {
            return $query->where('codigo', 'like', '%' . $codigo . '%');
        })
        ->when($detalle, function ($query) use ($detalle) {
            return $query->where('detalle', 'like', '%' . $detalle . '%');
        })
        ->when($rubro, function ($query) use ($rubro) {
            return $query->where('rubro', 'like', '%' . $rubro . '%');
        })
        ->when($proveedor, function ($query) use ($proveedor) {
            return $query->where('proveedor', 'like', '%' . $proveedor . '%');
        })
        ->when($marca, function ($query) use ($marca) {
            return $query->where('marca', 'like', '%' . $marca . '%');
        })        
        ->value('totalSINiva'); // Aquí se usa value en lugar de sum



        // Nombre del archivo
        $nombreArchivo = 'ventaXarticulos'.Carbon::now().'.pdf';

        $pdf = Pdf::loadView('PDF.pdfReporteVentaXarticulos',compact('fechas','articulos','totales','iva'));    

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

        $codigo = $this->codigo;
        $detalle = $this->detalle;
        $rubro = $this->rubro;
        $proveedor = $this->proveedor;
        $marca = $this->marca;


        return view('livewire.ventas.ver-ventas-articulos',[
            'articulos'=> productoComprobante::
                        select('*')
                        ->selectRaw(DB::raw('round(precio/(iva/100+1),2) as precioSINiva'))
                        ->where('empresa_id',Auth::user()->empresa_id)
                        ->whereBetween('fecha', [$this->fechaDesde, $this->fechaHasta])

                        ->when($codigo, function ($query) use ($codigo) {
                            return $query->where('codigo', 'like', '%' . $codigo . '%');
                        })
                        ->when($detalle, function ($query) use ($detalle) {
                            return $query->where('detalle', 'like', '%' . $detalle . '%');
                        })
                        ->when($rubro, function ($query) use ($rubro) {
                            return $query->where('rubro', 'like', '%' . $rubro . '%');
                        })
                        ->when($proveedor, function ($query) use ($proveedor) {
                            return $query->where('proveedor', 'like', '%' . $proveedor . '%');
                        })
                        ->when($marca, function ($query) use ($marca) {
                            return $query->where('marca', 'like', '%' . $marca . '%');
                        })
                        
                        ->paginate(50),

                        'iva'=> productoComprobante::                        
                        select(DB::raw('SUM(round( precio - (precio / (iva / 100 + 1)), 3)) as totalSINiva'))
                        ->where('empresa_id',Auth::user()->empresa_id)
                        ->whereBetween('fecha', [$this->fechaDesde, $this->fechaHasta])

                        ->when($codigo, function ($query) use ($codigo) {
                            return $query->where('codigo', 'like', '%' . $codigo . '%');
                        })
                        ->when($detalle, function ($query) use ($detalle) {
                            return $query->where('detalle', 'like', '%' . $detalle . '%');
                        })
                        ->when($rubro, function ($query) use ($rubro) {
                            return $query->where('rubro', 'like', '%' . $rubro . '%');
                        })
                        ->when($proveedor, function ($query) use ($proveedor) {
                            return $query->where('proveedor', 'like', '%' . $proveedor . '%');
                        })
                        ->when($marca, function ($query) use ($marca) {
                            return $query->where('marca', 'like', '%' . $marca . '%');
                        })
                        
                        ->value('totalSINiva'), // Aquí se usa value en lugar de sum

        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
