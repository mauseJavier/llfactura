<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

use Livewire\Attributes\Validate;
use Livewire\WithPagination;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Inventario;
use App\Models\Empresa;
use App\Models\ListaPrecio;
use App\Models\Rubro;
use App\Models\Proveedor;
use App\Models\Deposito;
use App\Models\Stock;
use App\Models\Marca;

class VerInventario extends Component
{

    use WithPagination;

    public $usuario;
    public $empresa;
    public $depositos;
    public $datoBuscado ='' ;
    public $modal = 'close';
    public $modalEditar = 'close';

    public $masDatos = false;
    public $modalStock = 'close';

    public $ordenarPor = 'created_at';
    public $acendenteDecendente= 'DESC';


    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:1', message: 'Minimo 1 caracter')]
    // #[Validate('max:250', message: 'Maximo 250 caracter')]
    public $codigo;
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:1', message: 'Minimo 1 caracter')]
    // #[Validate('max:250', message: 'Maximo 250 caracter')]
    public $detalle;
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Minimo 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $costo=0; //'required|numeric|min:0',
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Minimo 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $porcentaje1 =0;
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Minimo 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $precio1=0;
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Minimo 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $precio2=0;
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Minimo 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $precio3=0;    
    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Mayor a 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $iva;
    
    // #[Validate('required', message: 'Requerido')]
    public $rubro='General';
    // #[Validate('required', message: 'Requerido')]
    public $proveedor='General';
    // #[Validate('required', message: 'Requerido')]

    // #[Validate('required', message: 'Requerido')]
    // #[Validate('min:0', message: 'Mayor a 0')]
    // #[Validate('numeric', message: 'Solo Numeros')]
    public $nuevoStock=0;


    public $marca='General';
    public $pesable = 'no';
    public $controlStock = 'no';
    public $imagen;
    public $idArticulo;


    public $ivaIncluido = false;

    public $idDeposito;

    // GUARDAR RUBRO
    public $nuevoRubro = '';
    public $nuevoProveedor = '';
    public $nuevaMarca = '';

    public $nuevaLista='';
    public $porcentajeLista=0;

    //MODIFICAR EL STOCK



    public function exportarInventarioCsv(){
       

            // ak ahy que agregar las dos formas de pago 
    
            $filename = 'Inventario'.Carbon::now().'.csv';
    
            // Abrir o crear el archivo CSV
            $handle = fopen($filename, 'w');
    
            // "id" => 15650
            // "tipoComp" => "11"
            // "numero" => 2080
            // "total" => 14560.0
            // "cae" => 74432202688187
            // "fechaVencimiento" => "2024-11-03"
            // "fecha" => "2024-10-24 19:56:13"
            // "ptoVta" => 1
            // "deposito_id" => 1
            // "DocTipo" => 99
            // "cuitCliente" => 0
            // "razonSocial" => "Consumidor Final"
            // "tipoContribuyente" => 5
            // "domicilio" => null
            // "leyenda" => null
            // "idFormaPago" => 5
            // "remito" => "no"
            // "empresa_id" => 1
            // "usuario" => "JAVIER LLFACTURA"
            // "created_at" => "2024-10-24 19:56:13"
            // "updated_at" => "2024-10-24 19:56:13"
    
            // Agregar los encabezados al archivo CSV
            fputcsv($handle, ['id','codigo','detalle','costo','precio1','precio2','precio3','porcentaje','iva','rubro','proveedor','marca','pesable','controlStock','imagen','empresa_id','favorito','created_at','updated_at',]);
    
                $inventario = DB::table('inventarios')
                                // ->select('id','codigo','detalle','precio1 as precio')
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->whereAny([
                                    'codigo',
                                    'detalle',
                                    'rubro',
                                    'proveedor',
                                    'marca'
                                ], 'LIKE', "%$this->datoBuscado%")        
                                ->orderBy($this->ordenarPor,$this->acendenteDecendente)                        
                                ->get();
    
                //  dd($inventario);
    
            // Escribir los datos de la consulta en el archivo CSV
            foreach ($inventario as $item) {
    
                fputcsv($handle, [
                    $item->id,
                    $item->codigo,
                    $item->detalle,
                    $item->costo,
                    $item->precio1,
                    $item->precio2,
                    $item->precio3,
                    $item->porcentaje,
                    $item->iva,
                    $item->rubro,
                    $item->proveedor,
                    $item->marca,
                    $item->pesable,
                    $item->controlStock,
                    $item->imagen,             
                    $item->empresa_id,             
                    $item->favorito,
                    $item->created_at,
    
                    $item->updated_at,
    
                ]);
            }
    
            // Cerrar el archivo CSV
            fclose($handle);
    
            // Mensaje de confirmación
            return response()->download($filename)->deleteFileAfterSend(true);
    
        
    }

    public function exportarPLU(){
        

            // ak ahy que agregar las dos formas de pago 

            $filename = 'Inventario'.Carbon::now().'.csv';

            // Abrir o crear el archivo CSV
            $handle = fopen($filename, 'w');

            // "id" => 15650
            // "tipoComp" => "11"
            // "numero" => 2080
            // "total" => 14560.0
            // "cae" => 74432202688187
            // "fechaVencimiento" => "2024-11-03"
            // "fecha" => "2024-10-24 19:56:13"
            // "ptoVta" => 1
            // "deposito_id" => 1
            // "DocTipo" => 99
            // "cuitCliente" => 0
            // "razonSocial" => "Consumidor Final"
            // "tipoContribuyente" => 5
            // "domicilio" => null
            // "leyenda" => null
            // "idFormaPago" => 5
            // "remito" => "no"
            // "empresa_id" => 1
            // "usuario" => "JAVIER LLFACTURA"
            // "created_at" => "2024-10-24 19:56:13"
            // "updated_at" => "2024-10-24 19:56:13"

            // Agregar los encabezados al archivo CSV
            fputcsv($handle, ['Nombre de la sección:','Código de PLU','Descripción:','Número de PLU','Precio Lista 1','Precio Lista 2','Tipo de Venta','Vencimiento',]);

                $inventario = DB::table('inventarios')
                                // ->select('id','codigo','detalle','precio1 as precio')
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->where('pesable', 'si')

                                ->whereAny([
                                    'codigo',
                                    'detalle',
                                    'rubro',
                                    'proveedor',
                                    'marca'
                                ], 'LIKE', "%$this->datoBuscado%")        
                                ->orderBy($this->ordenarPor,$this->acendenteDecendente)                        
                                ->get();

                //  dd($inventario);

            // Escribir los datos de la consulta en el archivo CSV
            foreach ($inventario as $item) {

                fputcsv($handle, [
                    $item->rubro,
                    $item->codigo,
                    $item->detalle,
                    $item->codigo,
                    $item->precio1,
                    $item->precio2,
                    'PESO',
                    0,

                ]);
            }

            // Cerrar el archivo CSV
            fclose($handle);

            // Mensaje de confirmación
            return response()->download($filename)->deleteFileAfterSend(true);

        
    }

    public function eliminarInventario(Inventario $articulo){
        // dd($articulo);

        if(auth()->user()->role_id == 4 OR auth()->user()->role_id == 3){

            session()->flash('mensaje', 'Articulo Eliminado. ('.$articulo->codigo .') '.$articulo->detalle);
            $articulo->delete();

        }else{

            session()->flash('mensaje', 'Usuario No Autorizado Consultar Administrador. (Solo Admin-Plus)');


        }
    }

    public function cambiarModalStock($codigo = '',$detalle=''){        
        if($this->modalStock == 'open'){
            $this->modalStock = 'close';

            $this->nuevoStock = 0;
            $this->codigo = '';
            $this->detalle = '';

        }else{

            $this->codigo = $codigo;
            $this->detalle = $detalle;


            $this->modalStock = 'open';


        }
    }
    public function modificarStockArticulo(){

        // dump($this->codigo);
        // dump($this->detalle);
        // dump($this->idDeposito);
        // dump($this->nuevoStock);

               // Validaciones generales
               $this->validate([
                'codigo' => [
                    'required',
                    'min:1',
                    'max:250',
                ],
                'detalle' => 'required|min:1|max:250',
                'nuevoStock' => 'required|numeric|min:0',
            ], [
                // Mensajes personalizados
                'required' => 'Requerido',
                'min' => 'Minimo :min',
                'max' => 'Maximo :max caracteres',
                'numeric' => 'Solo Números',
            ]);

        if($this->nuevoStock > 0){

            $n = Stock::create([
                'codigo'=>trim($this->codigo),
                'detalle'=>$this->detalle,
                'deposito_id'=>$this->idDeposito,
                'stock'=>$this->nuevoStock,
                'comentario'=>'Ingreso',
                'usuario'=>$this->usuario->name,
                'empresa_id'=> $this->empresa->id,
            ]);
    
            $this->nuevoStock = 0;
    
            session()->flash('modificarStock', 'Stock Guardado. id-'. $n->id);

        }else{

            session()->flash('modificarStock', 'Stock NO puede ser 0.');

            
        }    


    }
    
    public function guardarMarca(){

        $validated = $this->validate([ 
            'nuevaMarca' => 'required|min:3',
        ]);

        $nuevaMarca = Marca::create([
            'nombre'=> $this->nuevaMarca,
            'comentario'=> $this->nuevaMarca,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($nuevaMarca){

            session()->flash('marcaGuardar', 'Marca '.$nuevaMarca->nombre.' Guardado.');
        }else{

            session()->flash('marcaGuardar', 'Ocurrio un Error');
        }


        $this->nuevaMarca = '';
    }

    public function guardarLista(){

        $validated = $this->validate([ 
            'nuevaLista' => 'required|min:3',
            'porcentajeLista' => 'required|numeric',
        ]);

        $listaGuardada = ListaPrecio::create([
            'nombre'=> $this->nuevaLista,
            'porcentaje'=> $this->porcentajeLista,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($listaGuardada){

            session()->flash('mensajeLista', 'Lista '.$listaGuardada->nombre.' Guardado.');
        }else{

            session()->flash('mensajeLista', 'Ocurrio un Error');
        }


        $this->nuevaLista = '';
        $this->porcentajeLista = 0;

    }

    public function guardarProveedor(){

        $validated = $this->validate([ 
            'nuevoProveedor' => 'required|min:3',
        ]);

        $nuevoProveedor = Proveedor::create([
            'nombre'=> $this->nuevoProveedor,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($nuevoProveedor){

            session()->flash('proveedorGuardado', 'Proveedor '.$nuevoProveedor->nombre.' Guardado.');
        }else{
            session()->flash('proveedorGuardado', 'Ocurrio un error');
        }
        $this->nuevoProveedor = '';
    }

    public function guardarRubro(){

        $validated = $this->validate([ 
            'nuevoRubro' => 'required|min:3',
        ]);

        $nuevoRubro = Rubro::create([
            'nombre'=> $this->nuevoRubro,
            'empresa_id'=>$this->empresa->id,
        ]);

        if($nuevoRubro){

            session()->flash('rubroGuardado', 'Rubro '.$this->nuevoRubro.' Guardado.');
        }else{
            session()->flash('rubroGuardado', 'Ocurrio un error');

        }

        $this->nuevoRubro = '';
    }

    public function mount()
    {
        $this->usuario = Auth::user();
        $this->empresa = Empresa::find(Auth::user()->empresa_id);
        $this->iva = $this->empresa->ivaDefecto;       
        $this->depositos = Deposito::where('empresa_id',$this->empresa->id)->get();

        $this->idDeposito = ($this->depositos[0]->id);

        
    }

    public function calcularPrecios(){

        // dd($this->ivaIncluido);

                // Validaciones generales
                $this->validate([

                    'costo' => 'required|numeric|min:0',
                    'porcentaje1' => 'required|numeric|min:0',
                    'precio1' => 'required|numeric|min:0',
                    'precio2' => 'required|numeric|min:0',
                    'precio3' => 'required|numeric|min:0',
                    'iva' => 'required|numeric|min:0',
  
                ], [
                    // Mensajes personalizados
                    'required' => 'Requerido',
                    'min' => 'Minimo :min',
                    'max' => 'Maximo :max caracteres',
                    'numeric' => 'Solo Números',
                ]);

        if($this->ivaIncluido){

            // $costo_mas_iva = round($this->costo / (1+ (doubleval($this->iva)/100)),2);
            $costo_mas_iva = round($this->costo,2);

        }else{
            $costo_mas_iva = round($this->costo +($this->costo * doubleval($this->iva) / 100),2);
        }

        // dump($costo_mas_iva);


        $this->precio1 = round( $costo_mas_iva + ($costo_mas_iva * $this->porcentaje1 /100) ,2);
        $this->precio2 = round( $this->precio1 + ($this->precio1 * $this->empresa->precio2 /100) ,2);
        $this->precio3 = round( $this->precio1 + ($this->precio1 * $this->empresa->precio3 /100) ,2);


    }

    public function editarId(inventario $articulo){
        // dd($articulo);
        // array:15 [▼
        //     "id" => 2
        //     "codigo" => "50733354"
        //     "detalle" => "maiores"
        //     "costo" => 979.36
        //     "precio1" => 99.67
        //     "precio2" => 71.53
        //     "precio3" => 138.17
        //     "iva" => 21.0
        //     "rubro" => "General"
        //     "proveedor" => "MAUSE"
        //     "pesable" => "si"
        //     "imagen" => null
        //     "empresa_id" => 1
        //     "created_at" => "2024-05-20 17:19:06"
        //     "updated_at" => "2024-05-20 17:19:06"
        // ]

        $this->idArticulo= $articulo->id;
        $this->codigo= $articulo->codigo;
        $this->detalle=$articulo->detalle;
        $this->costo=$articulo->costo;
        $this->precio1=$articulo->precio1;
        $this->precio2=$articulo->precio2;
        $this->precio3=$articulo->precio3;
        $this->porcentaje1=$articulo->porcentaje;
        $this->iva = $articulo->iva;
        $this->rubro=$articulo->rubro;
        $this->proveedor=$articulo->proveedor;
        $this->pesable = $articulo->pesable;
        $this->controlStock = $articulo->controlStock;
        $this->imagen=$articulo->imagen;
        $this->marca=$articulo->marca;



        $this->modalEditar="open";
    }


    public function buscarCodigoDuplicado(){

        $duplicado = Inventario::select('detalle')
        ->where('codigo', $this->codigo)
        ->where('empresa_id', $this->empresa->id)
        ->where('id','!=', $this->idArticulo)

        ->get();
        
        if(count($duplicado) > 0){
            
            $detalles='';
            foreach ($duplicado as $key => $value) {
                # code...
                $detalles .= $value->detalle .', ';
            }

            session()->flash('codigoDuplicado', 'Codigo duplicado. Articulos: '.$detalles);
        }


    }

    public function guardarArticulo(){

        // Validaciones generales
        $this->validate([
            'codigo' => [
                'required',
                'min:1',
                'max:250',
                function ($attribute, $value, $fail) {
                    // Validación adicional para códigos duplicados si empresa_id es 37
                    
                        $exists = Inventario::
                            where('codigo', $value)
                            ->where('empresa_id', $this->empresa->id)
                            ->exists();

                            // dd($exists);
                        if ($exists) {
                            $exists = Inventario::select('detalle')
                            ->where('codigo', $value)
                            ->where('empresa_id', $this->empresa->id)
                            ->get();
                            $fail("El código '{$value}' ya existe en el Inventario. Articulo: '{$exists[0]->detalle}'");
                        }
                    
                },
            ],
            'detalle' => 'required|min:1|max:250',
            'costo' => 'required|numeric|min:0',
            'porcentaje1' => 'required|numeric|min:0',
            'precio1' => 'required|numeric|min:0',
            'precio2' => 'required|numeric|min:0',
            'precio3' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
            'rubro' => 'required',
            'proveedor' => 'required',
            'nuevoStock' => 'required|numeric|min:0',
        ], [
            // Mensajes personalizados
            'required' => 'Requerido',
            'min' => 'Minimo :min',
            'max' => 'Maximo :max caracteres',
            'numeric' => 'Solo Números',
        ]);


        $this->controlStock = $this->nuevoStock > 0 ? 'si' : 'no';

            $nuevoArticulo = inventario::create(
                [
                    // 'id' => $this->idArticulo, 
                    'codigo' => trim($this->codigo),
                    'empresa_id' => $this->empresa->id,

                    'detalle'=> $this->detalle,
                    'costo'=> round($this->costo,2),
                    'precio1'=> round($this->precio1,2),
                    'precio2'=> round($this->precio2,2),
                    'precio3'=> round($this->precio3,2),
                    'porcentaje'=> round($this->porcentaje1,2),
                    'iva'=> round($this->iva,2),
                    'rubro'=> $this->rubro,
                    'proveedor'=> $this->proveedor,
                    'marca'=> $this->marca,
                    'pesable'=> $this->pesable,
                    'controlStock'=> $this->controlStock,
                    'imagen'=> $this->imagen,
                ]
            );

            $this->nuevoStock > 0 ? $this->modificarStockArticulo() : '';

        // $nuevoArticulo = inventario::create([
        //     'codigo'=> $this->codigo,
        //     'empresa_id'=> $this->empresa->id,

        //     'detalle'=> $this->detalle,
        //     'costo'=> round($this->costo,2),
        //     'precio1'=> round($this->precio1,2),
        //     'precio2'=> round($this->precio2,2),
        //     'precio3'=> round($this->precio3,2),
        //     'iva'=> round($this->iva,2),
        //     'rubro'=> $this->rubro,
        //     'proveedor'=> $this->proveedor,
        //     'pesable'=> $this->pesable,
        //     'imagen'=> $this->imagen,
        // ]);

        $this->idArticulo=0;
        $this->codigo='';
        $this->detalle='';
        $this->costo=0;
        $this->porcentaje1 =0;
        $this->precio1=0;
        $this->precio2=0;
        $this->precio3=0;
        $this->porcentaje1=0;
        $this->iva = $this->empresa->ivaDefecto;
        $this->rubro='General';
        $this->proveedor='General';
        $this->marca='General';
        $this->pesable = 'no';
        $this->controlStock = 'no';
        $this->imagen='';
        $this->ivaIncluido=false;

        $this->datoBuscado= $nuevoArticulo->codigo;
        $this->modal='close';
    }

    public function editarArticulo(){

        // Validaciones generales
        $this->validate([
            'codigo' => [
                'required',
                'min:1',
                'max:250',
                function ($attribute, $value, $fail) {
                    // Validación adicional para códigos duplicados si empresa_id es 37
                    
                        $exists = Inventario::
                            where('codigo', $value)
                            ->where('empresa_id', $this->empresa->id)
                            ->where('id','!=', $this->idArticulo)
                            ->exists();

                            // dd($exists);
                        if ($exists) {
                            $exists = Inventario::select('detalle')
                            ->where('codigo', $value)
                            ->where('empresa_id', $this->empresa->id)
                            ->get();
                            $fail("El código '{$value}' ya existe en el Inventario. Articulo: '{$exists[0]->detalle}'");
                        }
                    
                },
            ],
            'detalle' => 'required|min:1|max:250',
            'costo' => 'required|numeric|min:0',
            'porcentaje1' => 'required|numeric|min:0',
            'precio1' => 'required|numeric|min:0',
            'precio2' => 'required|numeric|min:0',
            'precio3' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
            'rubro' => 'required',
            'proveedor' => 'required',
            'nuevoStock' => 'required|numeric|min:0',
        ], [
            // Mensajes personalizados
            'required' => 'Requerido',
            'min' => 'Minimo :min',
            'max' => 'Maximo :max caracteres',
            'numeric' => 'Solo Números',
        ]);


        $articulo = Inventario::find($this->idArticulo);
 

        $articulo->codigo  = trim($this->codigo);
        $articulo->detalle = $this->detalle;
        $articulo->costo = round($this->costo,2);
        $articulo->precio1 = round($this->precio1,2);
        $articulo->precio2 = round($this->precio2,2);
        $articulo->precio3 = round($this->precio3,2);
        $articulo->porcentaje = round($this->porcentaje1,2);
        $articulo->iva = round($this->iva,2);
        $articulo->rubro = $this->rubro;
        $articulo->proveedor = $this->proveedor;
        $articulo->marca = $this->marca;
        $articulo->pesable = $this->pesable;
        $articulo->controlStock = $this->controlStock;
        $articulo->imagen = $this->imagen;

        
        $articulo->save();


            $this->nuevoStock > 0 ? $this->modificarStockArticulo() : '';

        //PARA ACTUALIZAR EL DETALLE DEL CODIGO EN LA TABLA STOCK Y NO SE GENERE MAL LA INFORMACION
        Stock::where('codigo', $this->codigo)
        ->where('empresa_id', $this->empresa->id)
        ->update(['detalle' => $this->detalle]);

        $this->idArticulo=0;
        $this->codigo='';
        $this->detalle='';
        $this->costo=0;
        $this->porcentaje1 =0;
        $this->precio1=0;
        $this->precio2=0;
        $this->precio3=0;
        $this->porcentaje1=0;
        $this->iva = $this->empresa->ivaDefecto;
        $this->rubro='General';
        $this->proveedor='General';
        $this->marca='General';
        $this->pesable = 'no';
        $this->controlStock = 'no';
        $this->imagen='';
        $this->ivaIncluido=false;

        $this->datoBuscado= $articulo->codigo;
        $this->modalEditar='close';
    }

    public function ordenarGrilla($ordenarPor){

        $this->ordenarPor = $ordenarPor;

        if($this->acendenteDecendente == 'DESC'){

            $this->acendenteDecendente = 'ASC';
        }else {
            $this->acendenteDecendente = 'DESC';
        }
        
    }

    public function cambioFavorito(Inventario $articulo,$favorito){
        // dd($idArticulo .' - '. $favorito);

        // $favorito ? dd($idArticulo .' - ES VERDADERO') : dd($idArticulo .' - ES FALSO');

        $articulo->favorito = !$favorito;
        $articulo->save();

        $this->render();


    }

    public function render()
    {
        // return view('livewire.inventario.ver-inventario');
        return view('livewire.inventario.ver-inventario',
        [     
            'inventario'=> DB::table('inventarios')
                                // ->select('id','codigo','detalle','precio1 as precio')
                                ->where('empresa_id', Auth::user()->empresa_id)
                                ->whereAny([
                                    'codigo',
                                    'detalle',
                                    'rubro',
                                    'proveedor',
                                    'marca'
                                ], 'LIKE', "%$this->datoBuscado%")        
                                ->orderBy($this->ordenarPor,$this->acendenteDecendente)                        
                                ->paginate(30),

            'listaPrecios' => ListaPrecio::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
            'listaRubros' => Rubro::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
            'listaProveedores' => Proveedor::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
            'listaMarcas' => Marca::where('empresa_id', $this->empresa->id)->orderBy('nombre', 'asc')->get(),
        ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

    public function cambiarModal(){

        if($this->modal == 'close'){
            $this->modal = 'open';
        }else{
            $this->modal = 'close';

            $this->codigo='';
            $this->detalle='';
            $this->costo=0;
            $this->porcentaje1 =0;
            $this->precio1=0;
            $this->precio2=0;
            $this->precio3=0;
            $this->iva = $this->empresa->ivaDefecto;
            $this->rubro='General';
            $this->proveedor='General';
            $this->pesable = 'no';
            $this->imagen='';
            $this->ivaIncluido= false;
            
        }
    }

    public function cambiarModalEditar(){

        if($this->modalEditar == 'close'){
            $this->modalEditar = 'open';
        }else{
            $this->modalEditar = 'close';

            $this->codigo='';
            $this->detalle='';
            $this->costo=0;
            $this->porcentaje1 =0;
            $this->precio1=0;
            $this->precio2=0;
            $this->precio3=0;
            $this->iva = $this->empresa->ivaDefecto;
            $this->rubro='General';
            $this->proveedor='General';
            $this->pesable = 'no';
            $this->imagen='';
            $this->ivaIncluido= false;
            
        }
    }

}
