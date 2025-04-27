<?php

namespace App\Livewire\Novedades;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

use App\Models\Novedad;
use App\Models\Empresa;

class Novedades extends Component
{

    public $id;
    public $titulo;
    public $detalle;
    public $nombreRuta;
    public $url;
    public $pie;
    public $usuario;
    public $aplicarA = 'todas'; // Por defecto, aplicar a todas las empresas
    public $empresaId; // ID de la empresa específica

    public function mount(){

        // Retrieve the currently authenticated user...
        $this->usuario = Auth::user();   

    }

    public function guardarNovedad(){
        if ($this->id) {
            // Editar la novedad existente
            $novedad = Novedad::find($this->id);
            if ($novedad) {
                $novedad->update([
                    'titulo' => $this->titulo,
                    'detalle' => $this->detalle,
                    'nombreRuta' => $this->nombreRuta,
                    'url' => $this->url,
                    'pie' => $this->pie,
                    'usuario' => Auth::user()->name
                ]);

                session()->flash('mensaje', 'Novedad actualizada correctamente.');
            } else {
                session()->flash('mensaje', 'Novedad no encontrada.');
            }
        } else {
            if ($this->aplicarA === 'una' && $this->empresaId) {
                // Crear una novedad para una empresa específica
                Novedad::create([
                    'empresa_id' => $this->empresaId,
                    'titulo' => $this->titulo,
                    'detalle' => $this->detalle,
                    'nombreRuta' => $this->nombreRuta,
                    'url' => $this->url,
                    'pie' => $this->pie,
                    'usuario' => Auth::user()->name
                ]);

                session()->flash('mensaje', 'Novedad creada para la empresa seleccionada.');
            } else {
                // Crear una novedad para todas las empresas
                $empresas = Empresa::all();

                foreach ($empresas as $empresa) {
                    Novedad::create([
                        'empresa_id' => $empresa->id,
                        'titulo' => $this->titulo,
                        'detalle' => $this->detalle,
                        'nombreRuta' => $this->nombreRuta,
                        'url' => $this->url,
                        'pie' => $this->pie,
                        'usuario' => Auth::user()->name
                    ]);
                }

                session()->flash('mensaje', 'Novedad creada para todas las empresas.');
            }
        }

        $this->resetInputFields();
    }

    private function resetInputFields() {
        $this->id = null;
        $this->titulo = '';
        $this->detalle = '';
        $this->nombreRuta = '';
        $this->url = '';
        $this->pie = '';
    }

    public function editar($id){
        $novedad = Novedad::find($id); // Usar el modelo Novedad para obtener la novedad por ID

        if ($novedad) {
            $this->id = $novedad->id;
            $this->titulo = $novedad->titulo;
            $this->detalle = $novedad->detalle;
            $this->nombreRuta = $novedad->nombreRuta;
            $this->url = $novedad->url;
            $this->pie = $novedad->pie;
        } else {
            session()->flash('mensaje', 'Novedad no encontrada.');
        }
    }

    public function eliminar($id){
        $novedad = Novedad::find($id);

        if ($novedad) {
            $novedad->delete();
            session()->flash('mensaje', 'Novedad eliminada correctamente.');
        } else {
            session()->flash('mensaje', 'Novedad no encontrada.');
        }
    }

    public function render()
    {
        $empresaId = Auth::user()->empresa_id; // Obtener la empresa del usuario logueado

        return view('livewire.novedades.novedades', [
            'novedades' => Novedad::where('empresa_id', $empresaId)
                                ->orderBy('id', 'DESC')
                                ->get(),
            'empresas' => Empresa::all(), // Pasar todas las empresas a la vista
        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
