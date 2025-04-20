<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;
    protected $guarded = [];


    protected static function booted()
    {
        static::creating(function ($stock) {
            // Obtener saldo actual

            
            
            $saldoActual = DB::table('stocks')
            ->select('saldo')
            ->where('codigo', $stock->codigo)
            ->where('deposito_id', $stock->deposito_id)
            ->where('empresa_id', $stock->empresa_id)
            ->orderBy('id', 'desc')
            ->value('saldo');
            
            if ($saldoActual == 0)  {
                
                $saldoActual = DB::table('stocks')
                ->select('saldo')
                ->where('codigo', $stock->codigo)
                ->where('deposito_id', $stock->deposito_id)
                ->where('empresa_id', $stock->empresa_id)
                ->sum('stock');

            }

                // dd($saldoActual, $sumaStock);

            // Asignar el nuevo saldo (saldo actual + cantidad ingresada)
            $stock->saldo = $saldoActual + $stock->stock;
        });
    }

        // Relación con Deposito
        public function deposito()
        {
            return $this->belongsTo(Deposito::class, 'deposito_id', 'id');
        }



}
