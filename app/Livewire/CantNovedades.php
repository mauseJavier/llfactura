<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Novedad;

class CantNovedades extends Component
{
    public $CantNovedades;

    public function mount()
    {
        $this->updateCantNovedades();
    }

    public function updateCantNovedades()
    {
        $empresaId = Auth::check() ? Auth::user()->empresa_id : null;
        $this->CantNovedades = $empresaId ? Novedad::where('empresa_id', $empresaId)->count() : 0;
    }

    public function render()
    {
        return view('livewire.cant-novedades');
    }
}
