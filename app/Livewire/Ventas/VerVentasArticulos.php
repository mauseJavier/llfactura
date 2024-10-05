<?php

namespace App\Livewire\Ventas;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Livewire\WithPagination;

// use App\Models\User;
// use App\Models\Empresa;
use App\Models\productoComprobante;


class VerVentasArticulos extends Component
{
    use WithPagination;

    public $fechaDesde,$fechaHasta, $precioVenta,
    $codigo,
    $detalle,
    $rubro,
    $proveedor;


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

        $res = DB::table('producto_comprobantes')
            ->select(DB::raw('SUM(CASE 
                        WHEN tipoComp IN (3, 8, 13,"notaRemito") THEN -precio * cantidad
                        ELSE precio * cantidad
                      END) as total_precio'))
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
            ->first();

            
            $this->precioVenta = $res->total_precio ?  number_format($res->total_precio, 2, ',', '.') :  number_format(0, 2, ',', '.');

    }

    public function render()
    {

        $codigo = $this->codigo;
        $detalle = $this->detalle;
        $rubro = $this->rubro;
        $proveedor = $this->proveedor;

        return view('livewire.ventas.ver-ventas-articulos',[
            'articulos'=> productoComprobante::where('empresa_id',Auth::user()->empresa_id)
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
                        
                        ->paginate(50)
        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
