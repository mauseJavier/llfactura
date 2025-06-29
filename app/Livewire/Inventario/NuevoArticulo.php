<?php

namespace App\Livewire\Inventario;

use Livewire\Component;
use App\Models\Inventario;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\ListaPrecio;
use App\Models\Rubro;
use App\Models\Proveedor;
use App\Models\Marca;

use App\Models\Deposito;
use App\Models\Stock;

use Livewire\Attributes\On; 


class NuevoArticulo extends Component
{
    use WithFileUploads;

        protected $listeners = ['eliminarImagen'];


    public $agregarVariosArticulos = false;
    
    public $articulo= null;
    public $idArticulo = null;
    public $codigo = '';
    public $detalle = '';
    public $costo = 0;
    public $precio1 = 0;
    public $precio2 = 0;
    public $precio3 = 0;
    public $porcentaje = 0;
    public $iva = 21;
    public $rubro = 'General';
    public $proveedor = 'General';
    public $marca = 'General';
    public $pesable = 'no';
    public $controlStock = 'no';
    public $imagen = null;
    public $imagenFile; // Para el archivo de imagen
    public $ivaIncluido = false;

    public $nuevoStock = 0;
    public $idDeposito = null;

    public $depositos = [];
    public $arrayImagen = [];


    public $empresa;

    public function mount($id = null)
    {
        $this->empresa = Empresa::find(Auth::user()->empresa_id);

        $this->iva = $this->empresa->ivaDefecto;  
        
        $this->ivaIncluido = $this->empresa->ivaIncluido == 'si' ? true : false;

        $this->depositos = Deposito::where('empresa_id',$this->empresa->id)->get();

        $this->idDeposito = ($this->depositos[0]->id);

        if ($id) {
            $this->articulo = Inventario::where('id', $id)
                ->where('empresa_id', Auth::user()->empresa_id)
                ->first();

            if (!$this->articulo) {
                session()->flash('mensaje', 'Artículo no encontrado.');
                return;
            }
            $this->idArticulo = $this->articulo->id;
            $this->codigo = $this->articulo->codigo;
            $this->detalle = $this->articulo->detalle;
            $this->costo = $this->articulo->costo;
            $this->precio1 = $this->articulo->precio1;
            $this->precio2 = $this->articulo->precio2;
            $this->precio3 = $this->articulo->precio3;
            $this->porcentaje = $this->articulo->porcentaje;
            $this->iva = $this->articulo->iva;
            $this->rubro = $this->articulo->rubro;
            $this->proveedor = $this->articulo->proveedor;
            $this->marca = $this->articulo->marca;
            $this->pesable = $this->articulo->pesable;
            $this->controlStock = $this->articulo->controlStock;
            $this->arrayImagen = json_decode($this->articulo->imagen, true) ?? [];
            $this->imagen = $this->articulo->imagen; // Mantener la imagen como string JSON

            // dd($articulo->imagen);

        }
        

    }

    public function cambiarAgregarVariosArticulos()
    {
        $this->agregarVariosArticulos = !$this->agregarVariosArticulos;
    }

    public function guardar()
    {

        $this->validate([
            'codigo'      => 'required|min:1|max:250',
            'detalle'     => 'required|min:1|max:250',
            'costo'       => 'required|numeric|min:0',
            'precio1'     => 'required|numeric|min:0',
            'precio2'     => 'required|numeric|min:0',
            'precio3'     => 'required|numeric|min:0',
            'porcentaje'  => 'required|numeric|min:0',
            'iva'         => 'required|numeric|min:0',
            'rubro'       => 'required',
            'proveedor'   => 'required',
            'marca'       => 'required',
            'imagenFile'  => 'nullable|image|max:2048',
        ], [
            'codigo.required'      => 'El código es obligatorio.',
            'codigo.min'           => 'El código debe tener al menos :min carácter.',
            'codigo.max'           => 'El código no puede superar los :max caracteres.',
            'detalle.required'     => 'El detalle es obligatorio.',
            'detalle.min'          => 'El detalle debe tener al menos :min carácter.',
            'detalle.max'          => 'El detalle no puede superar los :max caracteres.',
            'costo.required'       => 'El costo es obligatorio.',
            'costo.numeric'        => 'El costo debe ser un número.',
            'costo.min'            => 'El costo no puede ser negativo.',
            'precio1.required'     => 'El precio 1 es obligatorio.',
            'precio1.numeric'      => 'El precio 1 debe ser un número.',
            'precio1.min'          => 'El precio 1 no puede ser negativo.',
            'precio2.required'     => 'El precio 2 es obligatorio.',
            'precio2.numeric'      => 'El precio 2 debe ser un número.',
            'precio2.min'          => 'El precio 2 no puede ser negativo.',
            'precio3.required'     => 'El precio 3 es obligatorio.',
            'precio3.numeric'      => 'El precio 3 debe ser un número.',
            'precio3.min'          => 'El precio 3 no puede ser negativo.',
            'porcentaje.required'  => 'El porcentaje es obligatorio.',
            'porcentaje.numeric'   => 'El porcentaje debe ser un número.',
            'porcentaje.min'       => 'El porcentaje no puede ser negativo.',
            'iva.required'         => 'El IVA es obligatorio.',
            'iva.numeric'          => 'El IVA debe ser un número.',
            'iva.min'              => 'El IVA no puede ser negativo.',
            'rubro.required'       => 'El rubro es obligatorio.',
            'proveedor.required'   => 'El proveedor es obligatorio.',
            'marca.required'       => 'La marca es obligatoria.',
            'imagenFile.image'     => 'El archivo debe ser una imagen (jpg, jpeg, png).',
            'imagenFile.max'       => 'La imagen no puede superar los 2MB.',
        ]);


        // if ($this->imagenFile) {
        //     $idUnico = $this->idArticulo . '_' . time();
        //     $nombre = $idUnico . '_' .$this->detalle. '.'. $this->imagenFile->extension();
        //     $ruta = Auth::user()->empresa_id . '/'.$this->codigo ;
        //     $resultado = Storage::disk('cloudinary')->putFileAs(
        //         $ruta,
        //         $this->imagenFile,
        //         $nombre
        //     );

        //     $this->arrayImagen[] = "https://res.cloudinary.com/".env('CLOUDINARY_CLOUD_NAME') ."/image/upload/$resultado";

        //     $this->imagen = json_encode($this->arrayImagen); // Guardar como JSON en formato string
        // }

        if ($this->imagenFile) {
            $idUnico = $this->idArticulo . '_' . time();

            // Limpiar el detalle: quitar espacios y reemplazar los internos por guiones bajos
            $detalleLimpio = preg_replace('/\s+/', '_', trim($this->detalle));

            // Construir nombre sin espacios
            $nombre = $idUnico . '_' . $detalleLimpio . '.' . $this->imagenFile->extension();

            $ruta = Auth::user()->empresa_id . '/' . $this->codigo;

            $resultado = Storage::disk('cloudinary')->putFileAs(
                $ruta,
                $this->imagenFile,
                $nombre
            );

            $this->arrayImagen[] = "https://res.cloudinary.com/" . env('CLOUDINARY_CLOUD_NAME') . "/image/upload/$resultado";

            $this->imagen = json_encode($this->arrayImagen); // Guardar como JSON
        }




        

        if($this->nuevoStock != 0){

            // si el stock es distinto de 0, se crea el stock 
            $this->controlStock = 'si';

            $data = [
                'codigo' => trim($this->codigo),
                'empresa_id' => Auth::user()->empresa_id,
                'detalle' => $this->detalle,
                'costo' => round($this->costo, 2),
                'precio1' => round($this->precio1, 2),
                'precio2' => round($this->precio2, 2),
                'precio3' => round($this->precio3, 2),
                'porcentaje' => round($this->porcentaje, 2),
                'iva' => round($this->iva, 2),
                'rubro' => $this->rubro,
                'proveedor' => $this->proveedor,
                'marca' => $this->marca,
                'pesable' => $this->pesable,
                'controlStock' => $this->controlStock,
                'imagen' => $this->imagen ?? '',
            ];


        }else{

            $data = [
                'codigo' => trim($this->codigo),
                'empresa_id' => Auth::user()->empresa_id,
                'detalle' => $this->detalle,
                'costo' => round($this->costo, 2),
                'precio1' => round($this->precio1, 2),
                'precio2' => round($this->precio2, 2),
                'precio3' => round($this->precio3, 2),
                'porcentaje' => round($this->porcentaje, 2),
                'iva' => round($this->iva, 2),
                'rubro' => $this->rubro,
                'proveedor' => $this->proveedor,
                'marca' => $this->marca,
                'pesable' => $this->pesable,
                // 'controlStock' => $this->controlStock, NO modificamos el control de stock si no se modifica el stock
                'imagen' => $this->imagen ?? '',
            ];
            


        }


        if ($this->idArticulo) {
            // $articulo = Inventario::find($this->idArticulo);
            $this->articulo->update($data);

            //PARA ACTUALIZAR EL DETALLE DEL CODIGO EN LA TABLA STOCK Y NO SE GENERE MAL LA INFORMACION
            Stock::where('codigo', $this->codigo)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->update(['detalle' => $this->detalle]);


            session()->flash('mensaje', 'Artículo actualizado correctamente.');
        } else {

            // si el codigo ya existe no permitir crear el articulo
            $existeCodigo = Inventario::where('codigo', $this->codigo)
                ->where('empresa_id', Auth::user()->empresa_id)
                ->exists();
            if ($existeCodigo) {
                session()->flash('mensaje', 'El código ya existe. Por favor, utiliza otro código.');
                return;
            }else{

                // Crear un nuevo artículo
                Inventario::create($data);
                session()->flash('mensaje', 'Artículo creado correctamente.');
            }
        }

        $this->nuevoStock != 0 ? $this->modificarStockArticulo() : '';




        if ($this->agregarVariosArticulos) {
            // Si se está agregando varios artículos, limpiar los campos

            if ($this->idArticulo) {
                // Si ya existe un artículo, redirigir a la edición del mismo
                return redirect()->route('inventario.nuevo', ['id' => $this->idArticulo]);
            }else{

                return redirect()->route('inventario.nuevo');
            }

        } else {
            // Si no, redirigir a la lista de artículos
            return redirect()->route('inventario');
        }
        // $this->resetExcept('idArticulo');
        // dirigir a ruta inventario.nuevo 
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
                'nuevoStock' => 'required|numeric',
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
                    'usuario'=>Auth::user()->name,
                    'empresa_id'=> $this->empresa->id,
                ]);
        
                $this->nuevoStock = 0;
        
                session()->flash('modificarStock', 'Stock Guardado. id-'. $n->id);

            }elseif ($this->nuevoStock < 0) {
               
                $n = Stock::create([
                    'codigo'=>trim($this->codigo),
                    'detalle'=>$this->detalle,
                    'deposito_id'=>$this->idDeposito,
                    'stock'=>$this->nuevoStock,
                    'comentario'=>'Descuento Ajuste',
                    'usuario'=>Auth::user()->name,
                    'empresa_id'=> $this->empresa->id,
                ]);
        
                $this->nuevoStock = 0;
        
                session()->flash('modificarStock', 'Stock Guardado. id-'. $n->id);

            }          
            else{

                session()->flash('modificarStock', 'Stock NO puede ser 0.');

                
            }    


    }

    public function calcularPrecios()
    {
        $this->validate([
            'costo' => 'required|numeric|min:0',
            'porcentaje' => 'required|numeric|min:0',
            'precio1' => 'required|numeric|min:0',
            'precio2' => 'required|numeric|min:0',
            'precio3' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
        ], [
            'required' => 'Requerido',
            'min' => 'Minimo :min',
            'max' => 'Maximo :max caracteres',
            'numeric' => 'Solo Números',
        ]);

        if ($this->ivaIncluido) {
            $costo_mas_iva = round($this->costo, 2);
        } else {
            $costo_mas_iva = round($this->costo + ($this->costo * doubleval($this->iva) / 100), 2);
        }

        $this->precio1 = round($costo_mas_iva + ($costo_mas_iva * $this->porcentaje / 100), 2);
        $this->precio2 = round($this->precio1 + ($this->precio1 * $this->empresa->precio2 / 100), 2);
        $this->precio3 = round($this->precio1 + ($this->precio1 * $this->empresa->precio3 / 100), 2);
    }

        // crear funcion para elimiar de array de imagenes la igamen seleccionada
    #[On('eliminarImagen')] 
    public function eliminarImagen($index)
    {

        // elimar la imagen del storage
        if (isset($this->arrayImagen[$index])) {
            $imagenPath = $this->arrayImagen[$index];


            // quitar "https://res.cloudinary.com/dsen7wmue/image/upload/
            $imagenPath = str_replace('https://res.cloudinary.com/dsen7wmue/image/upload/', '', $imagenPath); 
            // $imagenPath = str_replace('.jpg', '', $imagenPath);
            // $imagenPath = str_replace('.png', '', $imagenPath);
            // $imagenPath = str_replace('.jpeg', '', $imagenPath);
            // $imagenPath = str_replace('.gif', '', $imagenPath);


            if (Storage::disk('cloudinary')->exists($imagenPath)) {
                Storage::disk('cloudinary')->delete($imagenPath);
            }else {
                // dd('La imagen no existe en el almacenamiento. '.$imagenPath);
                // session()->flash('error', 'La imagen no existe en el almacenamiento.');
            }
        }

        if (isset($this->arrayImagen[$index])) {

            unset($this->arrayImagen[$index]);

        }

        $nuevo  = []    ;
        foreach ($this->arrayImagen as $imagen) {
            $nuevo[] = $imagen;
            // dump($nuevo);
        }

        
        $this->imagen = json_encode($nuevo); // Guardar como JSON en formato string

        // dump($this->articulo->imagen);

        
        // $this->articulo->save();
        
        // dd();
        return redirect()->route('inventario.nuevo', ['id' => $this->articulo->id]);


    }


    public function render()
    {
        return view('livewire.inventario.nuevo-articulo', [
            'listaPrecios' => ListaPrecio::where('empresa_id', Auth::user()->empresa_id)
                ->select('*')
                ->distinct()
                ->orderBy('nombre', 'asc')
                ->get(),
            'listaRubros' => Rubro::where('empresa_id', Auth::user()->empresa_id)
                ->select('nombre')
                ->distinct()
                ->orderBy('nombre', 'asc')
                ->get(),
            'listaProveedores' => Proveedor::where('empresa_id', Auth::user()->empresa_id)
                ->select('nombre')
                ->distinct()
                ->orderBy('nombre', 'asc')
                ->get(),
            'listaMarcas' => Marca::where('empresa_id', Auth::user()->empresa_id)
                ->select('nombre')
                ->distinct()
                ->orderBy('nombre', 'asc')
                ->get(),
        ])
                ->extends('layouts.app')
        ->section('main'); 
    }
}
