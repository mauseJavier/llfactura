<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ControlDeRolAdmin;
use App\Http\Middleware\ControlDeRolSuper;

use App\Livewire\Panel;
use App\Livewire\Factura\NuevoComprobante;
use App\Livewire\Factura\Venta;
use App\Livewire\Comprobante\VerComprobante;
use App\Livewire\Comprobante\ProductosComprobante;
use App\Livewire\Usuarios\Usuarios;
use App\Livewire\Usuarios\Update;
use App\Livewire\Inventario\VerInventario;
use App\Livewire\Inventario\ImportarInventario;
use App\Livewire\Empresa\VerEmpresa;
use App\Livewire\Stock\VerStock;
use App\Livewire\Stock\MovimientoStock;
use App\Livewire\Stock\ImportarStock;
use App\Livewire\Stock\RecibirStock;
use App\Livewire\Stock\HistoricoEnvio;
use App\Livewire\Remito\VerRemito;


use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Comprobante;

use App\Http\Controllers\ComprobanteController;

use Illuminate\Support\Facades\Auth; //PARA PRUEBA
use Illuminate\Support\Facades\Storage; // para pruebas 
use Barryvdh\DomPDF\Facade\Pdf; //para pruebas
use chillerlan\QRCode\{QRCode, QROptions}; //PRUEBASSS


use Illuminate\Http\Request;
 


    Route::view('/', 'welcome');

    // Route::view('panel', Panel::class)
    //     ->middleware(['auth', 'verified'])
    //     ->name('panel');

    Route::view('prueba', 'layouts.prueba')
    ->middleware(['auth', 'verified'])
    ->name('prueba');




    Route::middleware(['auth', 'verified'])->group(function () {

        Route::middleware([ControlDeRolSuper::class])->group(function () {
            Route::get('/super', function () {
               
                return Auth::user();
            });
            
            Route::get('/usuarios', Usuarios::class)->name('usuarios');
            Route::get('/updateUsuario/{id}', Update::class)->name('updateUsuario');

            Route::get('/empresa', VerEmpresa::class)->name('empresa');

        

        });
 
        Route::middleware([ControlDeRolAdmin::class])->group(function () {
            Route::get('/admin', function () {
               
                return Auth::user();
            });

            Route::get('/inventario', VerInventario::class)->name('inventario');
            Route::get('/importarInventario', ImportarInventario::class)->name('importarInventario');
        

        });





        Route::get('/panel', Panel::class)->name('panel');
        Route::view('/profile', 'profile')->name('profile');        
    
        Route::get('/remitoscomprobante', VerRemito::class)->name('remitoscomprobante');

        Route::get('/comprobante', VerComprobante::class)->name('comprobante');
        Route::get('/productosComprobante/{idComprobante}', ProductosComprobante::class)->name('productosComprobante');
        Route::get('/nuevoComprobante', NuevoComprobante::class)->name('nuevoComprobante');
        Route::get('/venta', Venta::class)->name('venta');
        Route::get('/stock', VerStock::class)->name('stock');
        Route::get('/recibirstock', RecibirStock::class)->name('recibirstock');
        Route::get('/historicoenvio', HistoricoEnvio::class)->name('historicoenvio');
        Route::get('/movimientostock/{codigo?}', MovimientoStock::class)->name('movimientostock');
        Route::get('/importarstock', ImportarStock::class)->name('importarstock');



        Route::get('formatoPDF/{comprobante_id?}', function ($comprobante_id = null) {

            if($comprobante_id){
                
                return view('comprobante.formatoPDF',['comprobante_id'=>$comprobante_id])->render();

            }else{
                return redirect('comprobante')->with('mensaje', 'Sin elementos para mostrar factura');
            }
        })->name('formatoPDF');



                //para pruebas
        Route::get('/imprimirComprobante/{comprobante_id?}/{formato?}', [ComprobanteController::class, 'imprimir'])->name('imprimirComprobante');




        Route::get('/cuit/{cuit}', function ($cuit) {

            $empresa = Empresa::find(Auth::user()->empresa_id);
            
                if (Storage::disk('local')->exists('public/'.$empresa->cuit.'/cert.crt') ) {
                    // ...
                    $cert = Storage::get('public/'.$empresa->cuit.'/cert.crt');

                    // return response()->json($cert, 200);
                    
                }else
                {
                    return response()->json('no existe cert', 200);
                }

                if ( Storage::disk('local')->exists('public/'.$empresa->cuit.'/key.key')) {
                    // ...
                
                    $key = Storage::get('public/'.$empresa->cuit.'/key.key');

                    // return response()->json($key, 200);
                    
                }else
                {
                    return response()->json('no existe key', 200);
                }



                $afip = new Afip(array(
                    'CUIT' =>  $empresa->cuit,
                    'cert' => $cert,
                    'key' => $key,
                    'access_token' => env('tokenAFIPsdk'),
                    'production' => TRUE
                ));
            
            // CUIT del contribuyente
            // $tax_id = 20111111111;

            $taxpayer_details = $afip->RegisterInscriptionProof->GetTaxpayerDetails($cuit); 
            return $taxpayer_details;
            
        });


    });


    

require __DIR__.'/auth.php';
