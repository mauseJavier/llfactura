<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Session;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Empresa;



class Panel extends Component
{






    // #[Layout('layouts.app')] 
    public function render()
    {

        return view('livewire.panel', [

            'empresa'=>Empresa::find(Auth::user()->empresa_id),
        ])
        ->extends('layouts.app')
        ->section('main'); 


    }





}
