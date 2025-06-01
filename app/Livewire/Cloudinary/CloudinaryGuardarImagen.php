<?php

// namespace App\Livewire;
namespace App\Livewire\Cloudinary;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto;


use Illuminate\Support\Facades\Auth; // Si necesitas usar autenticación

class CloudinaryGuardarImagen extends Component
{
    use WithFileUploads;

    public $imagen;
    public $nombreImagen;
    public $codigoProducto;

    public function guardar()
    {
        $this->validate([
            'imagen' => 'image|max:2048', // 2MB
        ]);

        // Determinar el nombre base
        $nombre = $this->nombreImagen;
        if (empty($nombre)) {
            $nombre = pathinfo($this->imagen->getClientOriginalName(), PATHINFO_FILENAME);
        }

        //determinar el código del producto
        $codigo = $this->codigoProducto;
        if (empty($codigo)) {
            $codigo = 'producto_' . time(); // Generar un código único si no se proporciona
        }
        // Obtener extensión según mime type
        $extension = $this->imagen->extension();
        // Construir nombre: empresa_id_nombre.extensión
        $nombreFinal = $codigo . '_' . $nombre . '.' . $extension;

        // Subir a Cloudinary con el nombre deseado
        $resultado = Storage::disk('cloudinary')->putFileAs(
            Auth::user()->empresa_id .'/'. $codigo,
            $this->imagen,
            $nombreFinal
        );

        // Guardar $resultado en la base de datos, por ejemplo:
        // Producto::create([
        //     'nombre' => 'Producto X',
        //     'imagen_url' => $resultado,
        // ]);

        dd($resultado); // Para depurar y ver el resultado
    }

    public function render()
    {
        return view('livewire.cloudinary.cloudinary-guardar-imagen');
    }
}
