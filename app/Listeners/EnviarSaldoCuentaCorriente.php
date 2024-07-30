<?php

namespace App\Listeners;

use App\Events\SaldoCuentaCorriente;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\CuentaCorriente;


class EnviarSaldoCuentaCorriente
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SaldoCuentaCorriente $event): void
    {
        //

        // $credentials = tbl_usuario::select('rol')->orderBy('usu_id', 'desc')->first();

        $saldo = (CuentaCorriente::select('saldo')->where('cliente_id',$event->cliente_id)
                        ->where('empresa_id',$event->empresa_id)->orderBy('created_at', 'desc')->first());
        
        if(!isset($saldo['saldo'])){
            $saldo['saldo'] =0;
        }

        // dd($saldo['saldo']);
        CuentaCorriente::create([
            'empresa_id'=> $event->empresa_id,
            'cliente_id'=> $event->cliente_id,
            'comprobante_id'=> $event->comprobante_id,
            'tipo'=> $event->tipo,
            'comentario'=> $event->comentario,
            'debe'=> $event->debe,
            'haber'=> $event->haber,
            'saldo'=> round((doubleval($saldo['saldo']) + $event->haber )-($event->debe),2),

        ]);
    }
}
