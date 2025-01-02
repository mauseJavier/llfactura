<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;


use App\Models\Empresa;
use App\Models\Inventario;
use App\Models\Rubro;




class CartaInventarioController extends Controller
{
    

    public function index(Empresa $empresa ){


        // dump($empresa);

        // resources/views/CartaInventario/CartaInventario.blade.php
            return view('CartaInventario.CartaInventario', [
                'empresa' => $empresa,
                'inventario' => Inventario::where('empresa_id',$empresa->id)->get(),
                'rubro' => Rubro::where('empresa_id',$empresa->id)->get(),


        ]);

    }



    //cuando no trae parametro
    public function ejemplo(){


        $empresa = Empresa::find(1);

        dump($empresa);

                return view('user.profile', [
            'user' => User::findOrFail($id)
        ]);

    }
}
