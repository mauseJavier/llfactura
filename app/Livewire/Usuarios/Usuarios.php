<?php

namespace App\Livewire\Usuarios;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
// use App\Models\Empresa;
// use App\Models\Deposito;

use Illuminate\Support\Facades\DB;

class Usuarios extends Component
{

    // public $usuarios;
 
    // public $email;

    public $buscarUsuario;

    // public $deposito;

    public function eliminarUsuario(User $usuario){

        $usuario->delete();

        session()->flash('mensaje', 'Eliminado: '. $usuario->name .' Correo: '. $usuario->email);

    }
 
    public function mount()
    {
        // $this->usuarios = DB::select('SELECT a.id as usuarioId, c.nombre as rol, a.*,b.*,c.* FROM users a,empresas b, roles c WHERE a.empresa_id = b.id AND a.role_id = c.id');
 
        // $this->deposito = Deposito::where('empresa_id',Auth::user()->empresa_id)->get();
        
    }

    public function render()
    {

        if (Auth()->user()->role_id == 4) {

            $usuarios = DB::select("SELECT
                a.id AS usuarioId,
                c.nombre AS rol,
                a.*,
                b.*,
                c.*,
                d.nombre as nombreDeposito
            FROM
                users a,
                empresas b,
                roles c,
                depositos d
            WHERE
                a.empresa_id = b.id AND a.role_id = c.id  AND d.id = a.deposito_id AND
                (
                    a.name LIKE '%$this->buscarUsuario%' OR
                    a.email LIKE '%$this->buscarUsuario%' OR 
                    b.razonSocial LIKE '%$this->buscarUsuario%' OR
                    c.nombre LIKE '%$this->buscarUsuario%'
                )
            AND b.id = ".Auth::user()->empresa_id." 
            
            AND a.id != 1
            AND a.id != 2
            
            ORDER BY a.last_login DESC"
            );

        } else {
            $usuarios = DB::select("SELECT
                a.id AS usuarioId,
                c.nombre AS rol,
                a.*,
                b.*,
                c.*,
                d.nombre as nombreDeposito
            FROM
                users a,
                empresas b,
                roles c,
                depositos d
            WHERE
                a.empresa_id = b.id AND a.role_id = c.id  AND d.id = a.deposito_id AND
                (
                    a.name LIKE '%$this->buscarUsuario%' OR
                    a.email LIKE '%$this->buscarUsuario%' OR 
                    b.razonSocial LIKE '%$this->buscarUsuario%' OR
                    c.nombre LIKE '%$this->buscarUsuario%'
                )
            ORDER BY a.last_login DESC"
            );
        }
        
        return view('livewire.usuarios.usuarios',
            [
    
            'usuarios'=> $usuarios,

            
            ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

}
