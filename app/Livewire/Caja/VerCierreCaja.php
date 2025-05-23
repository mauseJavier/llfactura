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
use App\Models\CuentaCorriente;
use App\Models\Gasto;



class VerCierreCaja extends Component
{

    public $empresa;
    public $usuario;

    public $inicioTurno;
    public $finTurno;   
    // public $fechaCierre;


    public $importeCierre=0;
    public $importeInicio=0;



    public function mount(){

        $this->empresa = Empresa::find(Auth()->User()->empresa_id);
        $this->usuario = Auth()->User();



        if(Auth()->User()->role_id == 3 OR Auth()->User()->role_id == 4 OR Auth()->User()->role_id == 2){
            // $this->inicioTurno = Carbon::now()->format('Y-m-d H:i:s');
            // Fecha y hora de inicio del día actual
            $this->inicioTurno = Carbon::now()->startOfDay()->format('Y-m-d H:i');
    
            // Fecha y hora de fin del día actual
            $this->finTurno = Carbon::now()->endOfDay()->format('Y-m-d H:i');
    
            // dd($this->inicioTurno);


        }else {
            # code...
            // dd( Auth()->User()->last_login . ' '. Carbon::parse(Auth()->User()->last_login)->format('Y-m-d H:i'));

            $this->inicioTurno = Carbon::parse(Auth()->User()->last_login)->format('Y-m-d H:i:s');
    
            // Fecha y hora de fin del día actual
            $this->finTurno = Carbon::now()->format('Y-m-d H:i:s');

        }




    }



    public function inicioCaja(){

        $validated = $this->validate([ 
            'importeInicio' => 'required|min:1|numeric',
        ]);

        $inicio = CierreCaja::create([
            'descripcion'=>'Inicio de caja',

            'usuario_id'=>$this->usuario->id,
            'nombreUsuario'=>$this->usuario->name,
            'importe'=>$this->importeInicio * -1,
            'empresa_id'=>$this->usuario->empresa_id,
        ]);

        $this->importeInicio=0;

        session()->flash('mensaje', 'Inicio con Exito.');



    }


    public function cerrarCaja(){

        $validated = $this->validate([ 
            'importeCierre' => 'required|min:1|numeric',
        ]);

        $cierre = CierreCaja::create([

            'descripcion'=>'Cierre de caja',

            'usuario_id'=>$this->usuario->id,
            'nombreUsuario'=>$this->usuario->name,
            'importe'=>$this->importeCierre,
            'empresa_id'=>$this->usuario->empresa_id,
        ]);

        $this->importeCierre=0;

        session()->flash('mensaje', 'Cierre con Exito.');



    }

    // public function submit()
    // {
    //     $this->validate([
    //         'inicioTurno' => 'required|date',
    //         'finTurno' => 'required|date|after:inicioTurno',
    //     ]);

    //     // {{-- PARA EL TOMI QUE ES 44 EL REPORTE  --}}

    //     // Lógica para manejar el envío del formulario
    //     if (Auth::user()->empresa_id != 44) {
    //         return redirect()->route('reportes', ['ruta' => 'reporteVentaUsuarioCompleto', 'inicio' => $this->inicioTurno, 'fin' => $this->finTurno]);
    //     } else {
    //         return redirect()->route('reportes', ['ruta' => 'reporteVentaUsuario', 'inicio' => $this->inicioTurno, 'fin' => $this->finTurno]);
    //     }
    // }


    public function render()
    {

        $usuarios = User::where('empresa_id',$this->usuario->empresa_id)->orderBy('created_at','desc')->get();
        $cierreTodosUsuarios=[];

        foreach ($usuarios as $key => $us) {

            //////
            $collection = Comprobante::select('comprobantes.idFormaPago as idFormaPago', 'forma_pagos.nombre', DB::raw('SUM(comprobantes.importeUno) as totalImporte'))
            ->join('forma_pagos', 'comprobantes.idFormaPago', '=', 'forma_pagos.id')
            ->where('comprobantes.empresa_id', Auth::user()->empresa_id)
            // ->whereBetween('comprobantes.created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
            ->whereBetween('comprobantes.created_at', [$this->inicioTurno, $this->finTurno])

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
                    // ->whereBetween('comprobantes.created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                    ->whereBetween('comprobantes.created_at', [$this->inicioTurno, $this->finTurno])
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

                // SUMAR EL COMBRO DE CUENTAS CORRIENTES 

                $cobroCuentasCorrientes=0;
                $cobroCC = CuentaCorriente::where('usuario',$us->name)
                // ->whereDate('created_at', $this->fechaCierre)
                ->whereBetween('created_at', [$this->inicioTurno, $this->finTurno])
                ->where('formaPago','Efectivo')
                ->get();

                foreach ($cobroCC as $key => $value) {
                   # code...
                   $cobroCuentasCorrientes += $value->haber ;
                }

                // $totalSoloEfectivo += $cobroCuentasCorrientes;


            // dd(number_format($sumaTotal, 2, ',', '.'));
            // dd($totalSoloEfectivo);

            $sumaCierre = CierreCaja::where('usuario_id',$us->id)
            // ->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                ->whereBetween('created_at', [$this->inicioTurno, $this->finTurno])
                ->sum('importe');

            $cierres = CierreCaja::where('usuario_id',$us->id)
                // ->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                    ->whereBetween('created_at', [$this->inicioTurno, $this->finTurno])
                    ->get();

            $sumaGastos = Gasto::
                where('usuario',$us->name)
                ->where('formaPago','Efectivo')
                ->where('estado','Pago')
                ->where('empresa_id',Auth::user()->empresa_id)
                ->whereBetween('created_at', [$this->inicioTurno, $this->finTurno])
                ->sum('importe');


            $info=['titulo'=>'Reporte Diario:'. $us->name,
                    'usuario'=> $us->name,
                    'fechayhora'=>Carbon::now()->format('Y-m-d:H:i:s'),
                    'totales'=>$totales,
                    'sumaTotal'=>number_format($sumaTotal, 2, ',', '.'),
                    'cierres'=>$cierres,
                    'sumaCierre'=>number_format($sumaCierre, 2, ',', '.'),
                    'sumaGastos'=>number_format($sumaGastos, 2, ',', '.'),

                    'totalSoloEfectivo'=>number_format($totalSoloEfectivo, 2, ',', '.'),
                    'cobroCuentasCorrientes'=>number_format($cobroCuentasCorrientes, 2, ',', '.'),
                    'diferencia'=>number_format(($sumaCierre  + $sumaGastos) - ( $totalSoloEfectivo + $cobroCuentasCorrientes), 2, ',', '.'),

                ];

            $cierreTodosUsuarios[]=$info;

            //////
            
        }

        // dd( $cierreTodosUsuarios);

        return view('livewire.caja.ver-cierre-caja',[
                'cierres'=> CierreCaja::where('usuario_id',$this->usuario->id)
                    // ->whereBetween('created_at', [Carbon::createFromFormat('Y-m-d', $this->fechaCierre)->startOfDay(), Carbon::createFromFormat('Y-m-d',  $this->fechaCierre)->endOfDay()])
                        ->whereBetween('created_at', [$this->inicioTurno, $this->finTurno])
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
