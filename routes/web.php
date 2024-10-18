<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ControlDeRolAdmin;
use App\Http\Middleware\ControlDeRolSuper;

use App\Livewire\Panel;
use App\Livewire\Factura\NuevoComprobante;
use App\Livewire\Factura\Venta;
use App\Livewire\Comprobante\VerComprobante;
use App\Livewire\Comprobante\ProductosComprobante;
use App\Livewire\Comprobante\NotaCredito;
use App\Livewire\Usuarios\Usuarios;
use App\Livewire\Usuarios\Update;
use App\Livewire\Inventario\VerInventario;
use App\Livewire\Inventario\ImportarInventario;
use App\Livewire\Inventario\EdicionMultiple;
use App\Livewire\Inventario\CodigoBarra;

use App\Livewire\Empresa\VerEmpresa;
use App\Livewire\Stock\VerStock;
use App\Livewire\Stock\MovimientoStock;
use App\Livewire\Stock\ImportarStock;
use App\Livewire\Stock\RecibirStock;
use App\Livewire\Stock\HistoricoEnvio;
use App\Livewire\Remito\VerRemito;
use App\Livewire\Cliente\VerCliente;
use App\Livewire\Cliente\CuentaCorriente;

use App\Livewire\Proveedor\VerProveedor;
use App\Livewire\Presupuesto\VerPresupuesto;

use App\Livewire\FacturacionEmpresas\VerFacturacionEmpresas;
use App\Livewire\FacturacionEmpresas\EstadoEmpresa;


use App\Livewire\Ventas\VerVentasArticulos;



use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Comprobante;

use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReciboPdfController;
use App\Http\Controllers\CodigoDeBarraController;
use App\Http\Controllers\ReporteVentaUsuarioController;

use App\Models\Stock; //PARA PRUEBAS
use Illuminate\Support\Facades\Auth; //PARA PRUEBA
use Illuminate\Support\Facades\Storage; // para pruebas 
use Barryvdh\DomPDF\Facade\Pdf; //para pruebas
use chillerlan\QRCode\{QRCode, QROptions}; //PRUEBASSS
use App\Models\User; //PRUEBAS
use Illuminate\Support\Facades\DB; //PRUEBAS
use App\Models\Rubro; 
use App\Models\Proveedor;




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
            Route::get('/facturacionempresas', VerFacturacionEmpresas::class)->name('facturacionempresas');

            Route::get('/EstadoEmpresa', EstadoEmpresa::class)->name('EstadoEmpresa');



        

        });
 
        Route::middleware([ControlDeRolAdmin::class])->group(function () {
            Route::get('/admin', function () {
               
                return Auth::user();
            });

            Route::get('/inventario', VerInventario::class)->name('inventario');
            Route::get('/importarInventario', ImportarInventario::class)->name('importarInventario');
            Route::get('/edicionMultiple', EdicionMultiple::class)->name('edicionMultiple');
            Route::get('/reporteEdicionMultiple', [InventarioController::class, 'remporteEdicionMultiple'])->name('reporteEdicionMultiple');
            Route::get('/proveedor', VerProveedor::class)->name('proveedor');

            Route::get('/ventasArticulos', VerVentasArticulos::class)->name('ventasArticulos');
        

        });





        Route::get('/panel', Panel::class)->name('panel');
        Route::view('/profile', 'profile')->name('profile');        
    
        Route::get('/remitoscomprobante', VerRemito::class)->name('remitoscomprobante');

        Route::get('/comprobante', VerComprobante::class)->name('comprobante');
        Route::get('/notacredito/{comprobante}', NotaCredito::class)->name('notacredito');

        Route::get('/productosComprobante/{idComprobante}', ProductosComprobante::class)->name('productosComprobante');
        Route::get('/nuevoComprobante', NuevoComprobante::class)->name('nuevoComprobante');
        Route::get('/venta', Venta::class)->name('venta');
        Route::get('/stock', VerStock::class)->name('stock');
        Route::get('/recibirstock', RecibirStock::class)->name('recibirstock');
        Route::get('/historicoenvio', HistoricoEnvio::class)->name('historicoenvio');
        Route::get('/movimientostock/{stock_id?}', MovimientoStock::class)->name('movimientostock');
        Route::get('/importarstock', ImportarStock::class)->name('importarstock');
        Route::get('/cliente', VerCliente::class)->name('cliente');
        Route::get('/cuentaCorriente/{cliente}', CuentaCorriente::class)->name('cuentaCorriente');
        Route::get('/codigoBarra', CodigoBarra::class)->name('codigoBarra');


        Route::get('/reciboPdf/{recibo_id?}', [ReciboPdfController::class, 'imprimir'])->name('reciboPdf');
        Route::get('/codigoBarraPdf', [CodigoDeBarraController::class, 'imprimir'])->name('codigoBarraPdf');
        Route::get('/reporteVentaUsuario', [ReporteVentaUsuarioController::class, 'imprimir'])->name('reporteVentaUsuario');


        


        Route::get('/presupuesto', VerPresupuesto::class)->name('presupuesto');




        Route::get('formatoPDF/{tipo}/{comprobante_id?}', function ($tipo,$comprobante_id = null) {

            if($comprobante_id){

                return view('comprobante.formatoPDF',['comprobante_id'=>$comprobante_id,
                                                        'tipo'=>$tipo])->render();               

            }else{
                return redirect('comprobante')->with('mensaje', 'Sin elementos para mostrar factura');
            }
        })->name('formatoPDF');



                //para pruebas
        Route::get('/imprimirComprobante/{comprobante_id?}/{formato?}', [ComprobanteController::class, 'imprimir'])->name('imprimirComprobante');
        Route::get('/imprimirPresupuesto/{presupuesto_id?}/{formato?}', [PresupuestoController::class, 'imprimir'])->name('imprimirPresupuesto');





        Route::get('/pasarDomicilio', function () {

            $empresas= Empresa::all();

            foreach ($empresas as $key => $value) {
                dump($value->domicilio .' '.$value->razonSocial);

                $affected = DB::table('users')
                ->where('empresa_id',$value->id)
                ->update(['domicilio' => $value->domicilio]);

                dump('actualizado '. $affected);
            }

            
        });



        Route::get('/pasarRubros', function () {

            $articulos= DB::table('inventarios')
                        ->where('empresa_id' , Auth::user()->empresa_id)
                        
                        ->get();


            // dd($articulos[0]->rubro);

            foreach ($articulos as $key => $value) {

                // dump($value->rubro .' '.$value->proveedor);

                $r = Rubro::updateOrCreate(
                    ['nombre' => $value->rubro, ],
                    ['empresa_id' => Auth::user()->empresa_id,]
                );

                $p = Proveedor::updateOrCreate(
                    ['nombre' => $value->proveedor, ],
                    ['empresa_id' => Auth::user()->empresa_id,]
                );


            }

            
        });


        Route::get('/pasarPrecioLista', function () {

            dd('habilitar del codigo');

            $affectedRows = DB::table('producto_comprobantes')
            ->where('precioLista', 0)
            ->update(['precioLista' => DB::raw('precio')]);
        
            echo "Filas afectadas: producto_comprobantes" . $affectedRows;

            $affectedRows = DB::table('producto_presupuestos')
            ->where('precioLista', 0)
            ->update(['precioLista' => DB::raw('precio')]);
        
            echo "Filas afectadas: producto_presupuestos" . $affectedRows;
            

            
        });


    });


    

require __DIR__.'/auth.php';
