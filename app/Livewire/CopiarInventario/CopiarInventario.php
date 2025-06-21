<?php

namespace App\Livewire\CopiarInventario;

use Livewire\WithPagination;


use Livewire\Component;
use App\Models\Inventario;
// use App\Models\Rubro;
// use App\Models\Marca;
// use App\Models\Proveedor;

use App\Models\Empresa; // Si necesitas usar el modelo Empresa, descomentar esta línea

class CopiarInventario extends Component
{
        use WithPagination;


    public $empresa_id_origen;
    public $empresa_id_destino;
    public $cantidad = 10;

    public $empresaOrigen;
    public $empresaDestino;
    // public $articulosPreview = [];
    public $mostrarPreview = false;
    public $errores = [];

    public function buscarEmpresaOrigen()
    {
        $this->empresaOrigen = Empresa::find($this->empresa_id_origen);
        if (!$this->empresaOrigen) {


            session()->flash('error', 'Empresa origen no encontrada.');
        }
    }
    public function buscarEmpresaDestino()
    {
        $this->empresaDestino = Empresa::find($this->empresa_id_destino);
        if (!$this->empresaDestino) {
            session()->flash('error', 'Empresa destino no encontrada.');
        }
    }

    // public function buscarArticulos()
    // {
    //     $this->validate([
    //         'empresa_id_origen' => 'required|integer|different:empresa_id_destino',
    //         'empresa_id_destino' => 'required|integer|different:empresa_id_origen',
    //         'cantidad' => 'required|integer|min:1',
    //     ], [
    //         'empresa_id_origen.different' => 'La empresa de origen y la empresa destino no pueden ser la misma.',
    //         'empresa_id_destino.different' => 'La empresa de origen y la empresa destino no pueden ser la misma.'
    //     ]);

    //     $this->articulosPreview = Inventario::where('empresa_id', $this->empresa_id_origen)
    //         ->orderByDesc('updated_at')
    //         ->take($this->cantidad)
    //         ->paginate(20);
    //     $this->mostrarPreview = true;
    // }

    public function copiar()
    {
        $this->reset('errores');
        try {
            $this->validate([
                'empresa_id_origen' => 'required|integer|different:empresa_id_destino',
                'empresa_id_destino' => 'required|integer|different:empresa_id_origen',
                'cantidad' => 'required|integer|min:1',
            ], [
                'empresa_id_origen.different' => 'La empresa de origen y la empresa destino no pueden ser la misma.',
                'empresa_id_destino.different' => 'La empresa de origen y la empresa destino no pueden ser la misma.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->errores = $e->validator->errors()->all();
            return;
        }

        $articulos = Inventario::where('empresa_id', $this->empresa_id_origen)
            ->orderByDesc('updated_at')
            ->take($this->cantidad)
            ->get();

        $copiados = 0;
        $omitidos = 0;
        foreach ($articulos as $articulo) {
            $existe = \App\Models\Inventario::where('empresa_id', $this->empresa_id_destino)
                ->where('codigo', $articulo->codigo)
                ->exists();
            if ($existe) {
                $omitidos++;
                continue;
            }
            // Verificar o crear rubro en empresa destino
            if ($articulo->rubro) {
                \App\Models\Rubro::firstOrCreate([
                    'nombre' => $articulo->rubro,
                    'empresa_id' => $this->empresa_id_destino
                ]);
            }
            // Verificar o crear marca en empresa destino
            if ($articulo->marca) {
                \App\Models\Marca::firstOrCreate([
                    'nombre' => $articulo->marca,
                    'empresa_id' => $this->empresa_id_destino
                ]);
            }
            // Verificar o crear proveedor en empresa destino
            if ($articulo->proveedor) {
                \App\Models\Proveedor::firstOrCreate([
                    'nombre' => $articulo->proveedor,
                    'empresa_id' => $this->empresa_id_destino
                ]);
            }
            $nuevo = $articulo->replicate();
            $nuevo->empresa_id = $this->empresa_id_destino;
            $nuevo->save();
            $copiados++;
        }
        session()->flash('mensaje', "Se copiaron $copiados artículos y se omitieron $omitidos (ya existentes) de la empresa {$this->empresa_id_origen} a {$this->empresa_id_destino}.");
        $this->mostrarPreview = false;
        $this->articulosPreview = [];
    }

    public function render()
    {
        return view('livewire.copiar-inventario.copiar-inventario',[
            
        'articulosPreview' => Inventario::where('empresa_id', $this->empresa_id_origen)
            ->orderByDesc('updated_at')
            ->take($this->cantidad)
            ->get(),
        ])
            ->extends('layouts.app')
        ->section('main');
    }
}
