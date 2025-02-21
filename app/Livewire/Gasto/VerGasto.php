<?php

namespace App\Livewire\Gasto;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Livewire\WithPagination;



use App\Models\Gasto;
use App\Models\FormaPago;
use App\Models\Proveedor;




class VerGasto extends Component
{

    use WithPagination;


    public $tipo='';
    public $importe;
    public $idProveedor='';
    public $comentario;
    public $fechaNotificacion;
    public $formaPago='';
    public $estado='';
    public $fechaCreado='';
    public $buscar='';



    public function filtroFecha($filtro){

        $this->fechaCreado=$filtro;

    }
    
    public function guardarGasto(){


        $validated = $this->validate([ 
            'importe' => 'numeric|required|min:1',
            'tipo' => 'required|min:3',
            'estado' => 'required|min:3',
            'formaPago' => 'required|min:3',


        ], );

 
        $Gasto = Gasto::create([
            'tipo' => $this->tipo,
            'importe' => $this->importe,
            'formaPago' => $this->formaPago,
            'estado' => $this->estado,
            'idProveedor' => $this->idProveedor,
            'comentario' => $this->comentario,
            'fechaNotificacion' => $this->fechaNotificacion,
            'usuario' => Auth()->user()->name,
            'empresa_id' => Auth()->user()->empresa_id,


        ]);

        // dd($Gasto);

        session()->flash('creado', 'Creado.');


    }

    public function mount(){

        // dump(Proveedor::all());
    }


    public function render()
    {
        return view('livewire.gasto.ver-gasto',[
            'FormaPago'=>FormaPago::all(),            
            'Proveedor'=>Proveedor::where('empresa_id',Auth()->user()->empresa_id)->get(),

            'Gasto'=>DB::table('gastos')
            ->leftJoin('proveedors', 'gastos.idProveedor', '=', 'proveedors.id')
            ->where('gastos.empresa_id', Auth()->user()->empresa_id)
            ->whereAny([
                'proveedors.nombre',
                'gastos.usuario',
                
            ], 'like', '%'.$this->buscar.'%')

            ->when($this->tipo, function ($query, $tipo) {
                return $query->where('tipo', $tipo);
            })

            ->when($this->formaPago, function ($query, $formaPago) {
                return $query->where('formaPago', $formaPago);
            })

            ->when($this->estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })

            ->when($this->fechaCreado, function ($query, $fechaCreado) {
                switch ($fechaCreado) {
                    case 'Hoy':
                        return $query->whereDate('gastos.created_at', today());
                        break;
                    case 'Esta Semana':
                        return $query->whereBetween('gastos.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'Este Mes':
                        return $query->whereBetween('gastos.created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        break;
                    case 'Mes Pasado':
                        return $query->whereBetween('gastos.created_at', [
                            now()->subMonth()->startOfMonth(),
                            now()->subMonth()->endOfMonth()
                        ]);
                        break;                                        
                    default:
                        # cuando es un mes especifico
                        
                        return $query->whereYear('gastos.created_at', '=', Carbon::parse($fechaCreado)->format('Y'))
                                ->whereMonth('gastos.created_at', '=', Carbon::parse($fechaCreado)->format('m'));

                        break;
                }
            })

            ->select(
                'gastos.*', // Selecciona todas las columnas de tabla1
                'proveedors.nombre as nombreProveedor', // Renombra columna_compartida de tabla2
                'proveedors.id as proveedor_id' // Renombra otra columna de tabla2
            )
            ->OrderBy('created_at','Desc')
            ->paginate(10),

            'sumaImporte'=>DB::table('gastos')
            ->leftJoin('proveedors', 'gastos.idProveedor', '=', 'proveedors.id')
            ->where('gastos.empresa_id', Auth()->user()->empresa_id)
            ->whereAny([
                'proveedors.nombre',
                'gastos.usuario',
                
            ], 'like', '%'.$this->buscar.'%')

            ->when($this->tipo, function ($query, $tipo) {
                return $query->where('tipo', $tipo);
            })

            ->when($this->formaPago, function ($query, $formaPago) {
                return $query->where('formaPago', $formaPago);
            })

            ->when($this->estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })

            ->when($this->fechaCreado, function ($query, $fechaCreado) {
                switch ($fechaCreado) {
                    case 'Hoy':
                        return $query->whereDate('gastos.created_at', today());
                        break;
                    case 'Esta Semana':
                        return $query->whereBetween('gastos.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'Este Mes':
                        return $query->whereBetween('gastos.created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        break;
                    case 'Mes Pasado':
                        return $query->whereBetween('gastos.created_at', [
                            now()->subMonth()->startOfMonth(),
                            now()->subMonth()->endOfMonth()
                        ]);
                        break;                                        
                    default:
                        # cuando es un mes especifico

                        return $query->whereYear('gastos.created_at', '=', Carbon::parse($fechaCreado)->format('Y'))
                        ->whereMonth('gastos.created_at', '=', Carbon::parse($fechaCreado)->format('m'));
                
                        break;
                }
            })

            ->sum('gastos.importe'),


        ])        
        ->extends('layouts.app')
        ->section('main');
    }
}
