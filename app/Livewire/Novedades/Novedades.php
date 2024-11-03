<?php

namespace App\Livewire\Novedades;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
 

 
// // Retrieve the currently authenticated user's ID...
// $id = Auth::id();


class Novedades extends Component
{

    // $table->string('titulo', length: 100);
    // $table->string('detalle');
    // $table->string('nombreRuta');
    // $table->string('url');
    // $table->string('pie', length: 100);
    // $table->string('usuario', length: 100);

    public $id;
    public $titulo;
    public $detalle;
    public $nombreRuta;
    public $url;
    public $pie;
    public $usuario;


    public function mount(){

        // Retrieve the currently authenticated user...
        $this->usuario = Auth::user();   

    }

    public function guardarNovedad(){


        // dd($user);

        // DB::table('novedad')->insert([
        //     'titulo' => $this->titulo,
        //     'detalle' => $this->detalle,
        //     'nombreRuta' => $this->nombreRuta,
        //     'url' => $this->url,
        //     'pie' => $this->pie,
        //     'usuario' => Auth::user()->name,

        // ]);

        DB::table('novedad')->upsert(
            [
                // ['departure' => 'Oakland', 'destination' => 'San Diego', 'price' => 99],
                // ['departure' => 'Chicago', 'destination' => 'New York', 'price' => 150],
                [
                    'id' => $this->id,
                    'titulo' => $this->titulo,
                    'detalle' => $this->detalle,
                    'nombreRuta' => $this->nombreRuta,
                    'url' => $this->url,
                    'pie' => $this->pie,
                    'usuario' => Auth::user()->name,
        
                ]
            ],
            ['id',],
            ['titulo','detalle','nombreRuta','url','pie','usuario']
        );

        $this->id = null;
        $this->titulo = '';
        $this->detalle = '';
        $this->nombreRuta= '';
        $this->url= '';
        $this->pie= '';

        session()->flash('mensaje', 'Guardado.');


    }


    public function editar($id){

        $novedad = DB::table('novedad')
                ->where('id',$id)
                ->get();

                // dd($novedad[0]->titulo);
                $this->id = $novedad[0]->id;
                $this->titulo = $novedad[0]->titulo;
                $this->detalle = $novedad[0]->detalle;
                $this->nombreRuta= $novedad[0]->nombreRuta;
                $this->url= $novedad[0]->url;
                $this->pie= $novedad[0]->pie;



    }

    public function eliminar($id){
        // $deleted = DB::table('users')->delete();     
 
        $deleted = DB::table('novedad')->where('id',$id)->delete();

        session()->flash('mensaje', 'Novedad ELIMINADA.');

    }


    public function render()
    {
        return view('livewire.novedades.novedades',[
            'novedades'=> DB::table('novedad')
                        ->OrderBy('id','DESC')
                        ->get(),

        ])
        ->extends('layouts.app')
        ->section('main');
    }
}
