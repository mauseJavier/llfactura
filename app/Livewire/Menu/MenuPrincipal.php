<?php

namespace App\Livewire\Menu;

use App\Livewire\Actions\Logout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\Role;

class MenuPrincipal extends Component
{


    // public string $name = '';
    // public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        // $this->name = Auth::user()->name;
        // $this->email = Auth::user()->email;
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.menu.menu-principal',[
            'name'=> Auth::user()->name,
            'email'=> Auth::user()->email,
            'role'=> Role::find(Auth::user()->role_id),
            'empresa'=> Empresa::find(Auth::user()->empresa_id)
        ]);
    }
}
