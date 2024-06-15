<?php

namespace App\Livewire\Comprobante;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use Livewire\WithPagination;

use Livewire\Component;

use App\Models\Comprobante;

class VerComprobante extends Component
{
    use WithPagination;

    public $tiposComprobantes;

    public $fechaFiltroDesde;
    public $fechaFiltroHasta;

    public $tipoComp;
    public $usuarioFiltro;

    public function mount(){

        $this->fechaFiltroDesde = Carbon::now();
        $this->fechaFiltroDesde->setTime(0, 0);
        $this->fechaFiltroDesde = $this->fechaFiltroDesde->format('Y-m-d\TH:i');
        
        $this->fechaFiltroHasta = Carbon::now()->addDay()->format('Y-m-d\TH:i');

        $this->tipoComp = '';
        $this->usuarioFiltro = '';


        $array1 = [
            '1'=>'Factura A',
            '6'=>'Factura B',
            '11'=>'Factura C',
            'remito'=>'Remito',
        ];
        $array2=array();

        foreach (Comprobante::select('tipoComp')->distinct()->where('empresa_id', Auth::user()->empresa_id)->get() as $key => $value) {        
            array_push($array2, $value->tipoComp);
        }
      

        // Usamos array_intersect_key con array_flip para intersectar usando claves
        $this->tiposComprobantes = array_intersect_key($array1, array_flip($array2));




    }
    
    public function render()
    {
        return view('livewire.comprobante.ver-comprobante',[
            'comprobantes' => Comprobante::where('empresa_id', Auth::user()->empresa_id)
                                            ->where('created_at', '>=', $this->fechaFiltroDesde)
                                            ->where('created_at', '<=', $this->fechaFiltroHasta)
                                            ->when($this->tipoComp, function ($query, $tipoComp) {
                                                return $query->where('tipoComp', $tipoComp);
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
                                        ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
                                        ->sum('total'),

            'sumComprobantes' => Comprobante::select('tipoComp', DB::raw('SUM(total) as sumTotal'))
                                        ->where('empresa_id', Auth::user()->empresa_id)
                                        ->where('created_at', '>=', $this->fechaFiltroDesde)
                                        ->where('created_at', '<=', $this->fechaFiltroHasta)
                                        ->when($this->tipoComp, function ($query, $tipoComp) {
                                            return $query->where('tipoComp', $tipoComp);
                                        })
                                        ->where('usuario', 'like', '%' . $this->usuarioFiltro . '%')
                                        ->groupBy('tipoComp')
                                        ->get(),

            'totales'=> Comprobante::select('comprobantes.idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.total) as sumTotal'))
                                        ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
                                        ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                                        ->where('comprobantes.created_at', '>=', $this->fechaFiltroDesde)
                                        ->where('comprobantes.created_at', '<=', $this->fechaFiltroHasta)
                                        ->where('comprobantes.usuario', 'like', '%' . $this->usuarioFiltro . '%')
                                        ->when($this->tipoComp, function ($query, $tipoComp) {
                                            return $query->where('comprobantes.tipoComp', $tipoComp);
                                        })
                                        ->groupBy('comprobantes.idFormaPago','forma_pagos.nombre')
                                        ->get(),


            
        ])        
        ->extends('layouts.app')
        ->section('main');
    }
}
