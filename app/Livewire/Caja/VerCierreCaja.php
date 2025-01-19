<?php

namespace App\Livewire\Caja;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



use App\Models\Empresa;
use App\Models\CierreCaja;
use App\Models\User;
use App\Models\Comprobante;





class VerCierreCaja extends Component
{

    public $empresa;
    public $usuario;

    public $fechaCierre;

    public $importeCierre=0;


    public function mount(){

        $this->empresa = Empresa::find(Auth()->User()->empresa_id);
        $this->usuario = Auth()->User();

        $this->fechaCierre = Carbon::now()->format('Y-m-d');


        // dd($this->usuario);


    }



    public function cerrarCaja(){

        $validated = $this->validate([ 
            'importeCierre' => 'required|min:1|numeric',
        ]);

        $cierre = CierreCaja::create([
            'usuario_id'=>$this->usuario->id,
            'nombreUsuario'=>$this->usuario->name,
            'importe'=>$this->importeCierre,
            'empresa_id'=>$this->usuario->empresa_id,
        ]);

        $this->importeCierre=0;

        session()->flash('mensaje', 'Cierre con Exito.');



    }


    public function render()
    {

        $usuarios = User::where('empresa_id',$this->usuario->empresa_id)->get();
        $cierreTodosUsuarios=[];

        foreach ($usuarios as $key => $us) {

            //////
            $collection = Comprobante::select('comprobantes.idFormaPago as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporte'))
            ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
            ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
            ->whereBetween('comprobantes.created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
            ->where('comprobantes.usuario', 'like', '%' . $us->name . '%')
            // ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
            // ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
            // ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
            ->groupBy('comprobantes.idFormaPago', 'forma_pagos.nombre')
            
            // Unir la segunda colección
            ->unionAll(
                Comprobante::select('comprobantes.idFormaPago2 as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeDos) as totalImporte'))
                    ->join('forma_pagos', 'comprobantes.idFormaPago2', '=', 'forma_pagos.id')
                    ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
                    ->whereBetween('comprobantes.created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                    ->where('comprobantes.usuario', 'like', '%' . $us->name . '%')
                    // ->when($this->tipoComp, fn($query) => $query->where('comprobantes.tipoComp', $this->tipoComp))
                    // ->when($this->numeroComprobanteFiltro, fn($query) => $query->where('numero', '=', $this->numeroComprobanteFiltro))
                    // ->when($this->clienteComprobanteFiltro, fn($query) => $query->where('razonSocial', 'LIKE', '%' . $this->clienteComprobanteFiltro . '%'))
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

            // Inicializa una variable para almacenar la suma total
                $sumaTotal = 0;
                $totalSoloEfectivo = 0;


                // Itera sobre cada subarray y suma los valores de la clave 'total'
                foreach ($totales as $subarray) {
                    if (isset($subarray['total'])) {

                        $sumaTotal += $subarray['total'];

                        if($subarray['nombre'] == 'Efectivo'){
                            $totalSoloEfectivo += $subarray['total'];
                        }
                    }
                }


            // dd(number_format($sumaTotal, 2, ',', '.'));
            // dd($totalSoloEfectivo);

            $sumaCierre = CierreCaja::where('usuario_id',$us->id)
            ->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                // ->where('created_at',Carbon::now()->format('Y-m-d'))
                ->sum('importe');


            $info=['titulo'=>'Reporte Diario:'. $us->name,
                    'usuario'=> $us->name,
                    'fechayhora'=>Carbon::now()->format('Y-m-d:H:i:s'),
                    'totales'=>$totales,
                    'sumaTotal'=>number_format($sumaTotal, 2, ',', '.'),
                    'sumaCierre'=>number_format($sumaCierre, 2, ',', '.'),
                    'totalSoloEfectivo'=>number_format($totalSoloEfectivo, 2, ',', '.'),
                    'diferencia'=>number_format($sumaCierre - $totalSoloEfectivo, 2, ',', '.'),

                ];

            $cierreTodosUsuarios[]=$info;

            //////
            
        }

        return view('livewire.caja.ver-cierre-caja',[
                'cierreDia'=>CierreCaja::where('usuario_id',$this->usuario->id)
                                    ->whereBetween('created_at', [Carbon::now()->startOfDay() ,  Carbon::now()->endOfDay()])
                                        // ->where('created_at',Carbon::now()->format('Y-m-d'))
                                        ->get(),
                'sumaCierre'=>CierreCaja::where('usuario_id',$this->usuario->id)
                ->whereBetween('created_at', [Carbon::now()->startOfDay() ,  Carbon::now()->endOfDay()])
                    // ->where('created_at',Carbon::now()->format('Y-m-d'))
                    ->sum('importe'),
                'cierreTodosUsuarios'=> $cierreTodosUsuarios,
            ])
        ->extends('layouts.app')
        ->section('main');
    }
}
