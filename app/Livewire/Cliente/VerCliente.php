<?php

namespace App\Livewire\Cliente;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Asegúrate de importar Carbon para manejar fechas fácilmente       
use App\Models\CuentaCorriente;



use App\Models\Empresa;

use App\Models\Cliente;


class VerCliente extends Component
{

    public $idCliente;
    public $cuit;   
    public $razonSocial;    
    public $tipoDocumento=99;  
    public $domicilio;  
    public $correoCliente;  
    public $tipoContribuyente=5;  
    public $telefono;

    public $datoBuscado;

    public $ordenarPor = 'saldo';
    public $ordenarDireccion = 'asc';


    

    public function eliminarCliente(Cliente $cliente)
    {
        // Verificar si el cliente tiene saldo 
        // verifica el ultimo registro si tiene saldo mayor a 0

        $saldo = CuentaCorriente::where('cliente_id', $cliente->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->orderBy('created_at', 'desc')
            ->value('saldo');
        // $saldo = CuentaCorriente::where('cliente_id', $cliente->id)
        //     ->where('empresa_id', Auth::user()->empresa_id)
        //     ->sum('saldo');

        if ($saldo == 0) {
            $cliente->delete();
            //tambien tengo que borra todos los registros de cuenta corriente
            CuentaCorriente::where('cliente_id', $cliente->id)
                ->where('empresa_id', Auth::user()->empresa_id)
                ->delete();
            session()->flash('guardado', 'Cliente eliminado.');
        } else {
            session()->flash('error', 'Cliente no eliminado, tiene saldo.');
        }
    }


    public function ordenar($campo)
    {
        if ($this->ordenarPor === $campo) {
            $this->ordenarDireccion = $this->ordenarDireccion === 'asc' ? 'desc' : 'asc';
        } else {
            $this->ordenarPor = $campo;
            $this->ordenarDireccion = 'asc';
        }
    }


    public function editarCliente(Cliente $cliente){
        // dd($cliente);

        $this->idCliente = $cliente->id;

        $this->cuit = $cliente->numeroDocumento;
        $this->razonSocial = $cliente->razonSocial;
        $this->tipoDocumento = $cliente->tipoDocumento;
        $this->domicilio = $cliente->domicilio;
        $this->correoCliente = $cliente->correoCliente;
        $this->tipoContribuyente = $cliente->tipoContribuyente; 
        $this->telefono = $cliente->telefono;



    }

    public function cancelar(){

        $this->idCliente = '';

        $this->cuit = '';
        $this->razonSocial = '';
        $this->tipoDocumento = 99;
        $this->domicilio = '';
        $this->correoCliente = '';
        $this->tipoContribuyente = 5;
        $this->telefono = '';
    

    }

    public function updateCliente(){

        $validated = $this->validate([
            'cuit' => 'required|numeric|min:1',
            'razonSocial' => 'required|min:1',
        ], [
            'cuit.required' => 'El campo CUIT a enviar es obligatorio.',
            'cuit.numeric' => 'El campo CUIT a enviar debe ser un número.',
            'cuit.min' => 'El campo CUIT a enviar debe ser mayor que 0.',

            'razonSocial.required' => 'El campo Razon Social a enviar es obligatorio.',
            'razonSocial.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);


        // $cliente = Cliente::where('numeroDocumento', $this->cuit)
        //             ->where('empresa_id',Auth::user()->empresa_id)
        //             ->where('id','!=',$this->idCliente)
        //             ->get();

        //             // dd($cliente);


        // if ($cliente->isEmpty()) {

            // dd('no cuit');
            Cliente::where('id', $this->idCliente)
                    ->update(
                        [
            
                            'numeroDocumento'=>$this->cuit,
                        
                            'tipoDocumento'=>trim($this->tipoDocumento),
                
                            'razonSocial'=>trim($this->razonSocial),
                            'domicilio'=>trim($this->domicilio),
                            'correo'=>trim($this->correoCliente),
                            'tipoContribuyente'=>trim($this->tipoContribuyente),
                            'telefono'=>trim($this->telefono)
                        ]
                    );
    
    
    
            $this->idCliente='';
    
            $this->cuit='';
            $this->tipoDocumento=99;
            $this->razonSocial='';
            $this->domicilio='';
            $this->correoCliente='';
            $this->tipoContribuyente=5;
            $this->telefono='';
    
            session()->flash('guardado', 'Cliente Editado.');

            $this->redirectRoute('cliente');
            
        // }else {
                
    
        //     $this->idCliente='';
    
        //     $this->cuit='';
        //     $this->tipoDocumento=99;
        //     $this->razonSocial='';
        //     $this->domicilio='';
        //     $this->correoCliente='';
        //     $this->tipoContribuyente=5;
    
        //     session()->flash('guardado', 'Cliente NO editado, Exite otro cliente con el mismo cuit/dni.');

        //     $this->redirectRoute('cliente');

        // }


    }

    public function guardarCliente(){

        $validated = $this->validate([
            'cuit' => 'required|numeric|min:1',
            'razonSocial' => 'required|min:1',
            'tipoDocumento' => 'required',

        ], [
            'cuit.required' => 'El campo CUIT a enviar es obligatorio.',
            'cuit.numeric' => 'El campo CUIT a enviar debe ser un número.',
            'cuit.min' => 'El campo CUIT a enviar debe ser mayor que 0.',

            'razonSocial.required' => 'El campo Razon Social a enviar es obligatorio.',
            'razonSocial.min' => 'El campo Razon Social a enviar debe ser mayor que 0.',
        ]);


        // $cliente = Cliente::where('numeroDocumento', $this->cuit)
        //             ->where('empresa_id',Auth::user()->empresa_id)
        //             ->get();

        // // dd($cliente);

        // if ($cliente->isEmpty()) {
            # code...
            $cliente = Cliente::create(
                [
    
                    'numeroDocumento'=>$this->cuit,
                    'empresa_id'=> Auth::user()->empresa_id,
                
                    'tipoDocumento'=>trim($this->tipoDocumento),
        
                    'razonSocial'=>trim($this->razonSocial),
                    'domicilio'=>trim($this->domicilio),
                    'correo'=>trim($this->correoCliente),
                    'tipoContribuyente'=>trim($this->tipoContribuyente),
                    'telefono'=>trim($this->telefono)

                ]
            );
    
            $this->cuit='';
            $this->tipoDocumento=99;
            $this->razonSocial='';
            $this->domicilio='';
            $this->correoCliente='';
            $this->tipoContribuyente=5;
            $this->telefono='';
    
            session()->flash('guardado', 'Cliente '.$cliente->razonSocial.' Guardado.');

            $this->redirectRoute('cliente');

        // } else {
        //     # code...
    
        //     $this->cuit='';
        //     $this->tipoDocumento=99;
        //     $this->razonSocial='';
        //     $this->domicilio='';
        //     $this->correoCliente='';
        //     $this->tipoContribuyente=5;
        //     $this->telefono='';
    
        //     session()->flash('guardado', 'Cliente NO Guardado. Existe otro cliente con el mismo CUIT/DNI');
        //     $this->redirectRoute('cliente');

        // }
        


    }
    public function render()
    {

        // 1. Obtener la colección de clientes con su ÚLTIMO saldo
        $clientesConSaldo = DB::table('clientes')
        ->leftJoin('cuenta_corrientes', function ($join) {
            $join->on('clientes.id', '=', 'cuenta_corrientes.cliente_id')
                // Asegúrate que este subquery también filtre por empresa si es necesario
                // para rendimiento, aunque el where principal ya lo hace.
                ->whereRaw('cuenta_corrientes.id = (select max(id) from cuenta_corrientes where cliente_id = clientes.id and empresa_id = ?)', [Auth::user()->empresa_id]);
                // Usar max(id) suele ser más eficiente y robusto que max(created_at)
                // si puede haber registros con el mismo timestamp. Asume que IDs mayores son más recientes.
                // Si created_at es el único criterio fiable, vuelve a:
                // ->whereRaw('cuenta_corrientes.created_at = (select max(created_at) from cuenta_corrientes where cliente_id = clientes.id and empresa_id = ?)', [Auth::user()->empresa_id])
        })
        ->where('clientes.empresa_id', Auth::user()->empresa_id)
        ->where(function ($query) {
            // Mantén tu lógica de búsqueda si es necesaria aquí también,
            // aunque para las sumas totales quizás no quieras filtrar por $datoBuscado.
            // Si las sumas deben reflejar el filtro de búsqueda, mantenlo.
            // Si las sumas deben ser del TOTAL de clientes de la empresa, quita este where().
            if (!empty($this->datoBuscado)) { // Solo aplicar filtro si hay algo que buscar
                $query->where('clientes.numeroDocumento', 'LIKE', '%'.$this->datoBuscado.'%')
                    ->orWhere('clientes.razonSocial', 'LIKE', '%'.$this->datoBuscado.'%')
                    ->orWhere('clientes.domicilio', 'LIKE', '%'.$this->datoBuscado.'%');
            }
        })
        ->select('clientes.*', DB::raw('COALESCE(cuenta_corrientes.saldo, 0) as saldo'))
        // No necesitas ordenar aquí si solo vas a calcular sumas,
        // pero mantenlo si usas $clientesConSaldo directamente en la vista.
        ->orderBy($this->ordenarPor, $this->ordenarDireccion)
        ->get();

        // 2. Calcular las sumas a partir de la colección obtenida
        //    Usamos los métodos de la colección de Laravel, que son muy prácticos.
        $sumaSaldoNegativo = $clientesConSaldo->filter(function ($cliente) {
        return $cliente->saldo < 0;
        })->sum('saldo');

        $sumaSaldoPositivo = $clientesConSaldo->filter(function ($cliente) {
        return $cliente->saldo > 0;
        })->sum('saldo');


        // Calcular el inicio y fin del mes actual
        $inicioMesActual = Carbon::now()->startOfMonth();
        $finMesActual = Carbon::now()->endOfMonth();

        // Suma de pagos del mes actual
        $sumaPagosMesActual = DB::table('cuenta_corrientes')
            ->where('empresa_id', Auth::user()->empresa_id) // Filtrar por la empresa del usuario
            ->where('tipo', 'pago')                       // Filtrar solo los registros de tipo 'pago'
            ->whereBetween('created_at', [$inicioMesActual, $finMesActual]) // Filtrar por el rango de fechas del mes actual
            ->sum('haber'); // Sumar la columna 'haber' (asumiendo que aquí se registran los pagos)

        // Asegurarse de que el resultado sea un número (0 si no hubo pagos)
        $sumaPagosMesActual = $sumaPagosMesActual ?? 0;


        // 3. Pasar los datos a la vista
        return view('livewire.cliente.ver-cliente', [
        'clientes' => $clientesConSaldo, // Reutilizamos la colección ya obtenida
        'sumaSaldoNegativo' => $sumaSaldoNegativo,
        'sumaSaldoPositivo' => $sumaSaldoPositivo,
        'sumaPagosMesActual' => $sumaPagosMesActual, // Añadir la nueva suma
        ])
        ->extends('layouts.app')
        ->section('main');


    }
}
