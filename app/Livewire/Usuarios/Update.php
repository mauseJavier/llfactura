<?php

namespace App\Livewire\Usuarios;

use Livewire\Component;

// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Afip;

use App\Models\User;
use App\Models\Role;
use App\Models\Empresa;
use App\Models\Deposito;

class Update extends Component
{

    public $usuario,$name,$email,$empresa_id,$puntoVenta,$role_id, $domicilio;
   
    public $deposito_id;

    public $empresa;
    public $depositos;
    public $listaPuntoVenta;
 
    

    public function datosEmpresa($idEmpresa){

        $empresa = Empresa::find($idEmpresa);



        if($empresa->fe == 'si'){

            $afip = $this->objetoAfip($empresa);

            return $afip->ElectronicBilling->GetSalesPoints();
            // dump($sales_points[0]->Nro);
            // dump($sales_points[0]->EmisionTipo);
        }else{

            $dato[0] = (object) array('Nro' => 1,'EmisionTipo'=>'Nada');
            
            return $dato;
        }


    }

    function objetoAfip($empresa){
        // // Certificado (Puede estar guardado en archivos, DB, etc)
        // $cert = file_get_contents('./certificado.crt');

        // // Key (Puede estar guardado en archivos, DB, etc)
        // $key = file_get_contents('./key.key');

        // dd(Storage::disk('local')->exists('public/'.$this->empresa->cuit.'/ert.crt') );


        if (Storage::disk('local')->exists('public/'.$empresa->cuit.'/cert.crt') ) {
            // ...
            $cert = Storage::get('public/'.$empresa->cuit.'/cert.crt');

            // return response()->json($cert, 200);
            
        }else
        {
            dd('No existe certificado');
        }

        if ( Storage::disk('local')->exists('public/'.$empresa->cuit.'/key.key')) {
            // ...

            $key = Storage::get('public/'.$empresa->cuit.'/key.key');

            // return response()->json($key, 200);
            
        }else
        {
            dd('No existe key');
        }



        $afip = new Afip(array(
            'CUIT' => $empresa->cuit,
            'cert' => $cert,
            'key' => $key,
            'access_token' => env('tokenAFIPsdk'),
            'production' => TRUE
        ));

        return $afip;
    }

 
    public function mount($id)
    {
        $this->usuario = User::find($id);

        $this->name = $this->usuario->name;
        $this->email = $this->usuario->email;
        $this->empresa_id = $this->usuario->empresa_id;
        $this->puntoVenta = $this->usuario->puntoVenta;
        $this->role_id = $this->usuario->role_id;
        $this->deposito_id = $this->usuario->deposito_id;
        $this->domicilio = $this->usuario->domicilio;


        $this->empresa = Empresa::find($this->usuario->empresa_id);
        $this->depositos = Deposito::where('empresa_id', $this->empresa->id)->get();

        $this->listaPuntoVenta = $this->datosEmpresa($this->empresa->id);

    }

    public function buscarDepositos(){


        $this->depositos = Deposito::where('empresa_id', $this->empresa_id)->get();
        

        if($this->depositos->isEmpty()){
            $this->depositos = Deposito::create([
                'nombre'=> 'General',
                'comentario'=> 'General',
                'empresa_id'=> $this->empresa_id,
            ]);

            $this->depositos = Deposito::where('empresa_id', $this->empresa_id)->get();
        }

        
        $this->deposito_id = $this->depositos[0]->id;


        $empresa = Empresa::find($this->empresa_id);


        if($empresa->fe == 'si'){

            $this->listaPuntoVenta = $this->datosEmpresa($this->empresa_id);
            $this->puntoVenta = $this->listaPuntoVenta[0]->Nro;
        }else{

            $dato[0] = (object) array('Nro' => 1,'EmisionTipo'=>'Nada');
            $this->listaPuntoVenta = $dato;
            $this->puntoVenta = 0;
        }
        
        $domicilio = Empresa::select('domicilio')->where('id',$this->empresa_id)->get();
        $this->domicilio = $domicilio[0]->domicilio;

    }

    public function render()
    {
        return view('livewire.usuarios.update',
            [
    
            'roles'=> Role::all(),
            'empresas'=> Empresa::all(),
            
            ])        
        ->extends('layouts.app')
        ->section('main'); 
    }

    public function update() 
    {
        $this->usuario->name = $this->name;
        $this->usuario->email = $this->email;
        $this->usuario->empresa_id = $this->empresa_id;
        $this->usuario->puntoVenta = $this->puntoVenta;
        $this->usuario->role_id = $this->role_id;
        $this->usuario->deposito_id = $this->deposito_id;
        $this->usuario->domicilio = $this->domicilio;

 
        $this->usuario->save();
 
        // return redirect()->to('/usuarios', navigate: true)
        //      ->with('mensaje', 'Usuario Actualizado!');
        session()->flash('mensaje', 'Usuario Actualizado.');
        return $this->redirect('/usuarios', navigate: true);


    }


}
