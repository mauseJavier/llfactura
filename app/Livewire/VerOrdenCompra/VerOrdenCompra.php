<?php

namespace App\Livewire\VerOrdenCompra;

use Livewire\Component;

use Livewire\WithPagination;

use Livewire\Attributes\Session;



use App\Models\OrdenCompra;
use App\Models\ArticuloOrden;

use App\Models\Inventario;
use App\Models\Proveedor;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



class VerOrdenCompra extends Component
{

    use WithPagination;

    public $codigoDetalle;
    public $cantidad = 1;

    public $total = 0;

    public $nombreProveedor;
    public $direccionProveedor;
    public $emailProveedor;
    public $cuitProveedor;
    public $telefonoProveedor;

    public $listaProveedores;
    public $idProveedor = 0;


    #[Session(key: 'ordenDeCompra')] 
    public $ordenDeCompra;



    //crear dos funciones restarCantidad y sumarCantidad 
    public function restarCantidad ()   {

        $this->cantidad = $this->cantidad - 1;
        if ($this->cantidad = 0) {
            $this->cantidad = 0.1;
        }

    }

    public function sumarCantidad(){

        $this->cantidad = $this->cantidad + 1;
     


    }

    public function buscarCargar(){


        $articulo = Inventario::where('empresa_id',Auth::user()->empresa_id)
        ->where('codigo', $this->codigoDetalle)
        // ->whereAny([
        //     'codigo',
        //     'detalle',
        // ], 'like', '%'.$this->codigoDetalle.'%')
        ->get();

        if(count($articulo) == 1){

            // dd($this->ordenDeCompra['articulos']);
            // $this->emit('ordenDeCompra', $this->ordenDeCompra);
            // $this->emit('ordenDeCompra', $this->ordenDeCompra);
    
            $this->agregarOrdenCompra($articulo[0]->id);

            $this->codigoDetalle='';

        }
        

    }


    public function obtenerOrdenes()
    {
        $ordenesDEempresa = OrdenCompra::with('articulos')->where('empresa_id', 1)->get();
        // return response()->json($ordenes->where('id', 2)->first()->articulos);
        // return $ordenes->where('id', 2)->first()->articulos;
        return $ordenesDEempresa;
    }


    public function mount()
    {
        // $this->ordenDeCompra = session()->get('ordenDeCompra', []);

        // dd($this->obtenerOrdenes());


        $this->listaProveedores = Proveedor::where('empresa_id',Auth()->user()->empresa_id)->get();

        if(!isset($this->ordenDeCompra['articulos']))
        {
            $this->ordenDeCompra['articulos']= [];

        }

        $this->sumarTotal();


        if (isset($this->ordenDeCompra['proveedor']['nombre'])) {
            $this->nombreProveedor = $this->ordenDeCompra['proveedor']['nombre'];
            $this->direccionProveedor = $this->ordenDeCompra['proveedor']['direccion'];
            $this->emailProveedor = $this->ordenDeCompra['proveedor']['email'];
            $this->cuitProveedor = $this->ordenDeCompra['proveedor']['cuit'];
            $this->telefonoProveedor = $this->ordenDeCompra['proveedor']['telefono'];
        } else {
            $this->nombreProveedor = '';
            $this->direccionProveedor = ''; 
            $this->emailProveedor = '';
            $this->cuitProveedor = '';
            $this->telefonoProveedor = '';
            # code...
        }


    }

    public  function guardarDatosProveedor()
    {

        if ($this->idProveedor) {
            $proveedor = Proveedor::find($this->idProveedor);
            $this->nombreProveedor = $proveedor->nombre;
            $this->direccionProveedor = $proveedor->direccion; //falta agregar a la base de datos
            $this->emailProveedor = $proveedor->email; //falta agregar a la base de datos
            $this->cuitProveedor = $proveedor->cuit; //falta agregar a la base de datos
            $this->telefonoProveedor = $proveedor->telefono; //falta agregar a la base de datos

        }else{

            $this->validate([
                'nombreProveedor' => 'required',
                // 'direccionProveedor' => 'required',
                'emailProveedor' => 'email',
                // 'cuitProveedor' => 'required',
                // 'telefonoProveedor' => 'required',
            ]);
        }



        // Guardar los datos del proveedor en la sesión
        $this->ordenDeCompra['proveedor'] = [
            'nombre' => $this->nombreProveedor,
            'direccion' => $this->direccionProveedor,
            'email' => $this->emailProveedor,
            'cuit' => $this->cuitProveedor,
            'telefono' => $this->telefonoProveedor,
        ];

        // enviar mensaje flash a la vista 
        session()->flash('mensaje', 'Datos del proveedor guardados correctamente.');
    }



    public function agregarOrdenCompra($id){

        if($this->cantidad < 0.1 ){
            session()->flash('mensaje', 'Ingrese cantidad valida. Cantidad: '. $this->cantidad);

            return;

        }

        $articulo = Inventario::find($id);

        $nuevoArticulo = [
            'codigo' => $articulo->codigo,
            'detalle' => $articulo->detalle,

            'rubro' => $articulo->rubro,
            'proveedor' => $articulo->proveedor,
            'marca' => $articulo->marca,

            'cantidad' => $this->cantidad,
            'costo' => $articulo->costo,
            'subTotal' => $this->cantidad * $articulo->costo,
        ];


        array_unshift($this->ordenDeCompra['articulos'], $nuevoArticulo);

        // dd($this->ordenDeCompra['articulos']);


        $this->sumarTotal();
        // Reiniciar la cantidad a 1 después de agregar
        // $this->emit('ordenDeCompra', $this->ordenDeCompra);

        
        $this->cantidad = 1;

        
    }

    public function eliminarOrdenCompra($index)
    {
        unset($this->ordenDeCompra['articulos'][$index]);
        $this->ordenDeCompra['articulos'] = array_values($this->ordenDeCompra['articulos']);

        $this->sumarTotal();

    } 

    public function sumarTotal()
    {
        $this->total = 0;

        if (isset($this->ordenDeCompra['articulos'])) {
            # code...
            foreach ($this->ordenDeCompra['articulos'] as $item) {
                $this->total += $item['subTotal'];
            }
        }
    }

    public function borrarOrdenCompra()
    {
        $this->ordenDeCompra['articulos'] = [];
        $this->ordenDeCompra['proveedor'] = [];
        $this->total = 0;
    }


    //guardar en la base de datos el array de orden de compra en los modelos correspondientes use App\Models\OrdenCompra; use App\Models\ArticuloOrden;
    public function guardarOrdenCompra()
    {

        if (!isset($this->ordenDeCompra['proveedor']['nombre'])) {

            //si no exite el nombre del proveedor en la orden de compra que arroje el mensaje y salga de la funcion 
            session()->flash('mensaje', 'Por favor, complete los datos del proveedor antes de guardar la orden de compra.');
            return;
        }
        if (empty($this->ordenDeCompra['articulos'])) {
            //si no existe el array de articulos en la orden de compra que arroje el mensaje y salga de la funcion
            session()->flash('mensaje', 'Por favor, agregue al menos un artículo a la orden de compra antes de guardar.');
            return;
        }



        // Guardar la orden de compra en la base de datos   
        $ordenCompra = OrdenCompra::create([
            'empresa_id' => Auth::user()->empresa_id,

            'idProveedor' => $this->idProveedor,
            'proveedor' => $this->ordenDeCompra['proveedor']['nombre'],
            'cuit_proveedor' => $this->ordenDeCompra['proveedor']['cuit'],
            'direccion_proveedor' => $this->ordenDeCompra['proveedor']['direccion'],
            'email_proveedor' => $this->ordenDeCompra['proveedor']['email'],
            'telefono_proveedor' => $this->ordenDeCompra['proveedor']['telefono'],           
            'subtotal' => $this->total,
            'iva' => 0, // Cambia esto si necesitas calcular el IVA
            'total' => $this->total,
            'usuario'=> Auth::user()->name,
            'usuario_id'=> Auth::user()->id,
            'estado' => 'pendiente', // Estado de la orden de compra

        ]);


        // corregir los campos de la tabla de articulo orden con los siguientes
        // Schema::create('articulo_ordens', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('ordenCompraId')->constrained('orden_compras')->onDelete('cascade');
        //     $table->string('codigo');
        //     $table->string('detalle');
        //     $table->integer('cantidad');
        //     $table->decimal('costoUnitario', 10, 2);
        //     $table->decimal('subTotal', 10, 2);
        //     $table->timestamps();
        // });

        // dd($ordenCompra->id);


        foreach ($this->ordenDeCompra['articulos'] as $articulo) {
            ArticuloOrden::create([
                'ordenCompraId' => $ordenCompra->id,
                'codigo' => $articulo['codigo'],
                'detalle' => $articulo['detalle'],
                'rubro' => $articulo['rubro'],
                'proveedor' => $articulo['proveedor'],
                'marca' => $articulo['marca'],
                'cantidad' => $articulo['cantidad'],
                'costoUnitario' => $articulo['costo'],
                'subTotal' => $articulo['cantidad'] * $articulo['costo'],
                // Otros campos que necesites guardar
            ]);
        }

        $this->ordenDeCompra = [
            'articulos' => [],
            'proveedor' => [],
        ];
        $this->total = 0;
        // Guardar la orden de compra en la sesión
        // session()->put('ordenDeCompra', $this->ordenDeCompra);
        // session()->flash('mensaje', 'Orden de compra guardada correctamente.');

        $this->redirectRoute('reImprimirOrdenCompra', ['id' => $ordenCompra->id]);


    }

    
    public function render()
    {

        // $orden = OrdenCompra::with('articulos')->find($id);

        // // Ejemplo de uso
        // foreach ($orden->articulos as $articulo) {
        //     echo $articulo->descripcion;
        // }



        return view('livewire.ver-orden-compra.ver-orden-compra',[
            'inventario'=> Inventario::where('empresa_id',Auth::user()->empresa_id)
            // ->when($this->codigoDetalle, function ($query, $codigoDetalle) {
            //     return $query->where('','LIKE', $codigoDetalle);
            // })
            ->whereAny([
                'codigo',
                'detalle',
            ], 'like', '%'.$this->codigoDetalle.'%')
            ->paginate(5),
            

        ])
        ->extends('layouts.app')
        ->section('main'); 
    }
}
