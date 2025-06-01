<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CrearVerToken extends Component
{
    public $tokenActual;

    public function mount()
    {
        $user = Auth::user();

        // Obtener el token más reciente del usuario
        $latestToken = $user->tokens()->latest()->first();

        $this->tokenActual = $latestToken ? $latestToken->plainTextToken : null;
    }

    public function generarToken()
    {
        $user = Auth::user();

        // Revocar tokens anteriores
        $user->tokens()->delete();

        // Generar un nuevo token
        $this->tokenActual = $user->createToken('authToken')->plainTextToken;
    }

    public function render()
    {
        return view('livewire.crear-ver-token', [
            'tokenActual' => $this->tokenActual,
        ]);
    }
}
