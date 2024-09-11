<?php

namespace App\Livewire\Menu;

use App\Livewire\Actions\Logout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\Role;


use Livewire\Attributes\On;




class MenuPrincipal extends Component
{




    public $total=0;
    public $articulos=0;


    // public string $name = '';
    // public string $email = '';
    
    #[On('actualizarCarrito')] 
    public function actualizarCarrito($total,$articulos){

        // dd($carrito);
        $this->total = $total;
        $this->articulos = $articulos;

    }
    /**
     * Mount the component.
     */
    public function mount(): void
    {

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
