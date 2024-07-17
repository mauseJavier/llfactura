<?php

namespace App\Livewire\FacturacionEmpresas;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

use App\Models\Comprobante;
use App\Models\Empresa;


class VerFacturacionEmpresas extends Component
{
    use WithPagination;
    
    public function render()
    {
        return view('livewire.facturacion-empresas.ver-facturacion-empresas',[
            'comprobantes'=>  DB::table('comprobantes')
                ->join('empresas', 'comprobantes.empresa_id', '=', 'empresas.id')
                ->join('depositos', 'comprobantes.deposito_id', '=', 'depositos.id')
                ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
                ->select('comprobantes.*', 'empresas.razonSocial as empresa_razonSocial', 'depositos.nombre as deposito_nombre', 'forma_pagos.nombre as forma_pago_nombre')
                ->orderBy('created_at','DESC')
                ->paginate(20),
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
