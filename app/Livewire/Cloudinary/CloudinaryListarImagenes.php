<?php

// namespace App\Livewire;
namespace App\Livewire\Cloudinary;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Si necesitas usar autenticación


class CloudinaryListarImagenes extends Component
{
    public $imagenes = [];

    public function mount()
    {

        
        // Listar archivos del directorio 'imagenes' en el disco cloudinary
        $this->imagenes = Storage::disk('cloudinary')
        ->files(Auth::user()->empresa_id)
        ;

        // Obtener la URL de cada imagen
        // $this->imagenes = array_map(function ($imagen) {
        //     return Storage::disk('cloudinary')->path($imagen);
        // }, $this->imagenes);


    }

    public function render()
    {
        return view('livewire.cloudinary.cloudinary-listar-imagenes')
                ->extends('layouts.app')
        ->section('main'); 
    }
}
