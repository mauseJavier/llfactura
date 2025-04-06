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


    public $idGasto='';
    public $tipo='Gasto';
    public $importe;
    public $idProveedor;
    public $comentario;
    public $diaNotificacion;
    public $formaPago='';
    public $estado='';
    public $repetir='No';
    
    public $fechaCreado='';
    public $buscar='';    
    public $filtroTipo='';
    public $filtroRepetir='';

    public $nombreUsuario='';



    public function cancelar(){

        $this->idGasto='';
        $this->tipo='Gasto';
        $this->importe=0;
        $this->idProveedor='';
        $this->comentario='';
        $this->diaNotificacion;
        $this->formaPago='';
        $this->estado='';
        $this->repetir='No';
        
        $this->fechaCreado='';
        $this->buscar='';    
        $this->filtroTipo='';
        $this->filtroRepetir='';

    }
    public function editarGasto(Gasto $gasto){

        $this->idGasto=$gasto->id;


        $this->tipo = $gasto->tipo;     
        $this->importe = $gasto->importe;     
        $this->formaPago = $gasto->formaPago;     
        $this->estado = $gasto->estado;    
        $this->idProveedor = $gasto->idProveedor;     
        $this->comentario = $gasto->comentario;     
        $this->diaNotificacion = $gasto->diaNotificacion;     
        $this->usuario = $gasto->usuario;     
        $this->empresa_id = $gasto->empresa_id;    
        $this->repetir = $gasto->repetir;     

    }

    public function quitarRepetir(Gasto $gasto){

        $gasto->repetir = 'No';
        $gasto->save();

    }

    public function filtroFecha($filtro){

        $this->fechaCreado=$filtro;

    }
    
    public function guardarGasto(){


        $validated = $this->validate([ 
            'importe' => 'required|numeric|min:1',
            'tipo' => 'required|min:3',
            'estado' => 'required|min:3',
            'formaPago' => 'required|min:3',


        ], [
            'importe.required' => 'El campo Importe es obligatorio.',
            'importe.numeric' => 'El campo Importe debe ser un número.',
            'importe.min' => 'El campo Importe debe ser mayor que 0.',

            'formaPago.required' => 'El campo Forma Pago es obligatorio.',
            'estado.required' => 'El campo Estado es obligatorio.',



            // 'razonSocial.required' => 'El campo Razon Social a enviar es obligatorio.',
            // 'razonSocial.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);

        if($this->idGasto == ''){

            $Gasto = Gasto::create([
                'tipo' => $this->tipo,
                'importe' => $this->importe,
                'formaPago' => $this->formaPago,
                'estado' => $this->estado,
                'idProveedor' => $this->idProveedor,
                'comentario' => $this->comentario,
                'diaNotificacion' => $this->diaNotificacion,
                'usuario' => Auth()->user()->name,
                'empresa_id' => Auth()->user()->empresa_id,
                'repetir' => $this->repetir,
    
    
    
            ]);
        }else{

            // dd('ID a editar '.$this->idGasto);
            Gasto::where('id', $this->idGasto)
                ->update([
                    'tipo' => $this->tipo,
                    'importe' => $this->importe,
                    'formaPago' => $this->formaPago,
                    'estado' => $this->estado,
                    'idProveedor' => $this->idProveedor,
                    'comentario' => $this->comentario . 'Editado por: '. Auth()->user()->name,
                    'diaNotificacion' => $this->diaNotificacion,
                    // 'usuario' => Auth()->user()->name, LO SACO PARA QUE SALGA EL USUARIO ORIGINAL
                    // 'empresa_id' => Auth()->user()->empresa_id,
                    'repetir' => 'No',
                ]);


        }
 

        // dd($Gasto);

        $this->idGasto='';
        $this->tipo='Gasto';
        $this->importe;
        $this->idProveedor='';
        $this->comentario;
        $this->diaNotificacion;
        $this->formaPago='';
        $this->estado='';
        $this->repetir='No';
        
        $this->fechaCreado='';
        $this->buscar='';    
        $this->filtroTipo='';
        $this->filtroRepetir='';

        session()->flash('creado', 'Creado / Editado.');

        $this->redirect(route('gasto'));


    }

    public function mount(){

        if (Auth::user()->role_id == 1 ) {
            $this->nombreUsuario = Auth::user()->name;
        }

        // dd($this->nombreUsuario);
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
                            'gastos.tipo',
                            
                        ], 'like', '%'.$this->buscar.'%')

                        ->when($this->filtroTipo, function ($query, $filtroTipo) {
                            return $query->where('tipo', $filtroTipo);
                        })

                        ->when($this->formaPago, function ($query, $formaPago) {
                            return $query->where('formaPago', $formaPago);
                        })

                        ->when($this->estado, function ($query, $estado) {
                            return $query->where('estado', $estado);
                        })
                        ->when($this->filtroRepetir, function ($query, $filtroRepetir) {
                            return $query->where('repetir', $filtroRepetir);
                        })
                        ->when($this->nombreUsuario, function ($query, $nombreUsuario) {
                            return $query->where('usuario', $nombreUsuario);
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
                            'gastos.tipo',
                            
                        ], 'like', '%'.$this->buscar.'%')

                        ->when($this->filtroTipo, function ($query, $filtroTipo) {
                            return $query->where('tipo', $filtroTipo);
                        })

                        ->when($this->formaPago, function ($query, $formaPago) {
                            return $query->where('formaPago', $formaPago);
                        })

                        ->when($this->estado, function ($query, $estado) {
                            return $query->where('estado', $estado);
                        })
                        ->when($this->filtroRepetir, function ($query, $filtroRepetir) {
                            return $query->where('repetir', $filtroRepetir);
                        })
                        ->when($this->nombreUsuario, function ($query, $nombreUsuario) {
                            return $query->where('usuario', $nombreUsuario);
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
                
            'tiposUnicos' => Gasto::where('empresa_id', Auth::user()->empresa_id)
                        ->distinct()
                        ->pluck('tipo'),          


        ])        
        ->extends('layouts.app')
        ->section('main');
    }
}
