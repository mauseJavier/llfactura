<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ControlDeRolAdmin;
use App\Http\Middleware\ControlDeRolSuper;
use App\Http\Middleware\ControlDeRolAdminPlus;


use App\Livewire\Panel;
use App\Livewire\Factura\NuevoComprobante;
use App\Livewire\Factura\Venta;
use App\Livewire\Comprobante\VerComprobante;
use App\Livewire\Comprobante\ProductosComprobante;
use App\Livewire\Comprobante\NotaCredito;
use App\Livewire\Comprobante\FacturarRemito;

use App\Livewire\Usuarios\Usuarios;
use App\Livewire\Usuarios\Update;
use App\Livewire\Inventario\VerInventario;
use App\Livewire\Inventario\ImportarInventario;
use App\Livewire\Inventario\EdicionMultiple;
use App\Livewire\Inventario\CodigoBarra;
use App\Livewire\Inventario\NuevoArticulo;

use App\Livewire\Empresa\VerEmpresa;
use App\Livewire\Stock\VerStock;
use App\Livewire\Stock\MovimientoStock;
use App\Livewire\Stock\ImportarStock;
use App\Livewire\Stock\RecibirStock;
use App\Livewire\Stock\HistoricoEnvio;
use App\Livewire\Remito\VerRemito;
use App\Livewire\Cliente\VerCliente;
use App\Livewire\Cliente\CuentaCorriente;

use App\Livewire\Gasto\VerGasto;

use App\Jobs\EnviarPdfComprobanteJob;



use App\Livewire\Caja\VerCierreCaja;

use App\Livewire\Novedades\Novedades;


use App\Livewire\PreciosPublico\PreciosPublico;


use App\Livewire\Proveedor\VerProveedor;
use App\Livewire\Presupuesto\VerPresupuesto;

use App\Livewire\FacturacionEmpresas\VerFacturacionEmpresas;
use App\Livewire\FacturacionEmpresas\EstadoEmpresa;


use App\Livewire\Ventas\VerVentasArticulos;

use App\Livewire\Configuracion\Basico;

use App\Livewire\Mesas\VerMesas;
use App\Livewire\Mesas\ModificarMesa;
use App\Livewire\Mesas\Comandas;


use App\Livewire\Reportes\Reportes;
use App\Livewire\Backup\BackupManager;

//ORDEN DE COMPRA 
// app/Livewire/VerOrdenCompra/VerOrdenCompra.php
use App\Livewire\VerOrdenCompra\VerOrdenCompra;
use App\Livewire\VerOrdenCompra\HistorialOrdenCompra;

use App\Livewire\CopiarInventario\CopiarInventario;


use App\Livewire\Cloudinary\CloudinaryListarImagenes;


use App\Models\Empresa;
use App\Models\FormaPago;
use App\Models\Comprobante;

use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReciboPdfController;
use App\Http\Controllers\CodigoDeBarraController;
use App\Http\Controllers\ReporteVentaUsuarioController;
use App\Http\Controllers\ImprimirComandaController;
use App\Http\Controllers\ImprimirMesaController;

use App\Http\Controllers\ImprimirOrdenCompra;



use App\Http\Controllers\CartaInventarioController;

use App\Http\Controllers\CargarComprobanteController;

use App\Models\Stock; //PARA PRUEBAS
use Illuminate\Support\Facades\Auth; //PARA PRUEBA
use Illuminate\Support\Facades\Storage; // para pruebas 
use Barryvdh\DomPDF\Facade\Pdf; //para pruebas
use chillerlan\QRCode\{QRCode, QROptions}; //PRUEBASSS
use App\Models\User; //PRUEBAS
use Illuminate\Support\Facades\DB; //PRUEBAS
use App\Models\Rubro; 
use App\Models\Proveedor;
use App\Models\Inventario;





use Illuminate\Http\Request;
 


    Route::view('/', 'welcome');

    // Route::view('panel', Panel::class)
    //     ->middleware(['auth', 'verified'])
    //     ->name('panel');

    Route::view('prueba', 'layouts.prueba')
    ->middleware(['auth', 'verified'])
    ->name('prueba');


    Route::get('/cartaInventario', [CartaInventarioController::class, 'empresas'])->name('cartaInventario');

    Route::get('/cartaInventario/{empresa}', [CartaInventarioController::class, 'index'])->name('cartaInventarioEmpresa');




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


            Route::get('/backup', BackupManager::class)->name('backup');

            Route::get('/copiarInventario', CopiarInventario::class)->name('copiarInventario');



            Route::get('/verEmpresa', function () {

                $empresas= Empresa::all();

                foreach ($empresas as $key => $value) {
                    
                    dump($value->razonSocial);
                }

                
            });


            Route::get('/buscarDuplicados', function () {

                $empresas= Empresa::all();

                // ESTE CODIGO PARA BUSCAR LOS DUPLICADOS;

                foreach ($empresas as $key => $value) {
                    dump($value->id .' '.$value->razonSocial);

                    $affected = DB::select('                    
                        SELECT  nombre, empresa_id, COUNT(*) AS cantidad
                        FROM proveedors
                        WHERE empresa_id = ?
                        GROUP BY nombre, empresa_id
                        HAVING cantidad > 1', 
                        [$value->id]);



                    if(count($affected) > 0){

                        dump(' proveedores: '.count($affected));
    
                        dump($affected);
                    }

                    $affected = DB::select('                    
                        SELECT  nombre, empresa_id, COUNT(*) AS cantidad
                        FROM rubros
                        WHERE empresa_id = ?
                        GROUP BY nombre, empresa_id
                        HAVING cantidad > 1', 
                        [$value->id]);



                        if(count($affected) > 0){

                            dump(' rubros: '.count($affected));
        
                            dump($affected);
                        }

                    $affected = DB::select('                    
                        SELECT  nombre, empresa_id, COUNT(*) AS cantidad
                        FROM marcas
                        WHERE empresa_id = ?
                        GROUP BY nombre, empresa_id
                        HAVING cantidad > 1', 
                        [$value->id]);



                        if(count($affected) > 0){

                            dump(' marcas: '.count($affected));
        
                            dump($affected);
                        }


                    
                    
                }


                
            });

            Route::get('/eliminarDuplicados/{idEmpresa?}', function ($idEmpresa = null) {


                if($idEmpresa == null){

                    $empresas= Empresa::all();

                    // ESTE CODIGO PARA BUSCAR LOS DUPLICADOS;
    
                    foreach ($empresas as $key => $value) {
                        dump($value->id .' '.$value->razonSocial);
    


                        // ESTE CODIGO PARA ELIMINAR LOS DUPLICADOS COMPLETAR CON EL ID EMPRESA PARA NO ERRRAR 
                        
                        $resultadoEliminado = DB::affectingStatement('
                                DELETE r1 FROM proveedors r1
                                INNER JOIN proveedors r2
                                WHERE
                                    r1.empresa_id = '. $value->id .' AND
                                    r1.empresa_id = r2.empresa_id AND
                                    r1.nombre = r2.nombre AND
                                    r1.id > r2.id
                            ');
                            
        
                            if($resultadoEliminado > 0){
                                dump('Cantidad de eliminados proveedores : '.$resultadoEliminado);
                            }
        
        
                        $resultadoEliminado = DB::affectingStatement('
                                DELETE r1 FROM rubros r1
                                INNER JOIN rubros r2
                                WHERE
                                    r1.empresa_id = '. $value->id .' AND
                                    r1.empresa_id = r2.empresa_id AND
                                    r1.nombre = r2.nombre AND
                                    r1.id > r2.id
                            ');
                            
        
                            if($resultadoEliminado > 0){
                                dump('Cantidad de eliminados rubros : '.$resultadoEliminado);
                            }    

                            $resultadoEliminado = DB::affectingStatement('
                                DELETE r1 FROM marcas r1
                                INNER JOIN marcas r2
                                WHERE
                                    r1.empresa_id = '. $value->id .' AND
                                    r1.empresa_id = r2.empresa_id AND
                                    r1.nombre = r2.nombre AND
                                    r1.id > r2.id
                            ');
                            
        
                            if($resultadoEliminado > 0){
                                dump('Cantidad de eliminados marcas : '.$resultadoEliminado);
                            }    
    
                        
                        
                    }

                }else{

                    $empresa= Empresa::find($idEmpresa);
    
                    dump('Empresa id: '.$empresa->id .' Nombre: '.$empresa->razonSocial);
    
    
                    // ESTE CODIGO PARA ELIMINAR LOS DUPLICADOS COMPLETAR CON EL ID EMPRESA PARA NO ERRRAR 
                    
                    $resultadoEliminado = DB::affectingStatement('
                            DELETE r1 FROM proveedors r1
                            INNER JOIN proveedors r2
                            WHERE
                                r1.empresa_id = '. $idEmpresa .' AND
                                r1.empresa_id = r2.empresa_id AND
                                r1.nombre = r2.nombre AND
                                r1.id > r2.id
                        ');
                        
    
                    dump('Cantidad de eliminados proveedores : '.$resultadoEliminado);
    
    
                    $resultadoEliminado = DB::affectingStatement('
                            DELETE r1 FROM rubros r1
                            INNER JOIN rubros r2
                            WHERE
                                r1.empresa_id = '. $idEmpresa .' AND
                                r1.empresa_id = r2.empresa_id AND
                                r1.nombre = r2.nombre AND
                                r1.id > r2.id
                        ');
                        
    
                    dump('Cantidad de eliminados rubros : '.$resultadoEliminado);

                    $resultadoEliminado = DB::affectingStatement('
                            DELETE r1 FROM marcas r1
                            INNER JOIN marcas r2
                            WHERE
                                r1.empresa_id = '. $idEmpresa .' AND
                                r1.empresa_id = r2.empresa_id AND
                                r1.nombre = r2.nombre AND
                                r1.id > r2.id
                        ');
                        

                    dump('Cantidad de eliminados marcas : '.$resultadoEliminado);
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

            // Crear una ruta para ver los logs del archivo storage/logs/laravel.log
            Route::get('/verLogs', function () {
                $logFile = storage_path('logs/laravel.log');
                if (file_exists($logFile)) {
                    $logs = file_get_contents($logFile);
                    $formattedLogs = nl2br(e($logs)); // Convert newlines to <br> and escape HTML

                    return response()->view('logs.view', ['logs' => $formattedLogs]);
                } else {
                    return response('El archivo de logs no existe.', 404);
                }
            })->name('verLogs');


            Route::post('/logs/clear', function () {
                $logPath = storage_path('logs/laravel.log');
                if (file_exists($logPath)) {
                    file_put_contents($logPath, '');
                }
                // Borra todos los logs adicionales si existen
                foreach (glob(storage_path('logs/*.log')) as $file) {
                    file_put_contents($file, '');
                }
                return redirect()->back()->with('mensaje', 'Todos los logs han sido borrados.');
            })->name('logs.clear');


            Route::get('/configurarTelefono', function () {

                $empresas= Empresa::all();


                // dd($empresas);

                $reporte = [
                    'exitos' => [],
                    'errores' => []
                ];

                foreach ($empresas as $key => $value) {

                    // dump('KEY: '.$key .' ID:'.$value->id .' TELEFONO:'.$value->telefono .' TEL NOTIFI:'. $value->telefonoNotificacion);
                    // Schema::table('empresas', function (Blueprint $table) {
                    //     $table->string('ingresosBrutos')->nullable();
                    //     $table->string('telefonoNotificacion')->nullable();
                    //     $table->string('instanciaWhatsapp')->nullable();
                    //     $table->string('tokenWhatsapp')->nullable();
                    //     // $table->boolean('ivaIncluido')->default(false);
                    //     $table->enum('ivaIncluido', ['si', 'no'])->default('no')->after('ivaDefecto');
            
                    // });

                    foreach ($empresas as $key => $value) {
                        try {
                            $actualizado = Empresa::where('id', $value->id)
                                ->update([
                                    'telefonoNotificacion' => $value->telefono,
                                    'ingresosBrutos' => $value->cuit,
                                ]);
                
                            if ($actualizado) {
                                $reporte['exitos'][] = "Empresa ID {$value->id} actualizada correctamente.";
                            } else {
                                $reporte['errores'][] = "No se pudo actualizar la Empresa ID {$value->id}.";
                            }
                        } catch (\Exception $e) {
                            $reporte['errores'][] = "Error al actualizar la Empresa ID {$value->id}: " . $e->getMessage();
                        }
                    }
                
                    // Loguear el reporte
                    // Log::info('Reporte de actualización de empresas:', $reporte);
                
                    // Mostrar el reporte en pantalla
                    return response()->json($reporte);


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

            Route::get('/detalleStock/{idEmpresa}', function ($idEmpresa) {

                $inventario= Inventario::where('empresa_id',$idEmpresa)->get();

                // dump($inventario);

                foreach ($inventario as $key => $value) {
                    # code...
                    // dump($value->detalle);

                    Stock::where('codigo', $value->codigo)
                    ->where('empresa_id', $idEmpresa)
                    ->update(['detalle' => $value->detalle]);


                }

                dump('listo');

                // dump('Empresa id: '.$empresa->id .' Nombre: '.$empresa->razonSocial);


                // // ESTE CODIGO PARA ELIMINAR LOS DUPLICADOS COMPLETAR CON EL ID EMPRESA PARA NO ERRRAR 
                
                // // Encuentra todos los registros duplicados excepto uno por grupo
                //     $idEmpresaParaEliminarDuplicados = $idEmpresa;
                //     $duplicados = Inventario::select('codigo', 'empresa_id', DB::raw('MIN(id) as id'))
                //     ->where('empresa_id', $idEmpresaParaEliminarDuplicados)
                //     ->groupBy('codigo', 'empresa_id')
                //     ->pluck('id');

                // // Elimina los registros que no están en la lista de IDs únicos

                // $resultadoEliminado = Inventario::where('empresa_id', $idEmpresaParaEliminarDuplicados)
                //     ->whereNotIn('id', $duplicados)
                //     ->delete();

                // dump('Cantidad de eliminados: '.$resultadoEliminado);
                
            });

            Route::get('/pasarFechaHoraProductosComprobantes', function () {

                $articulos= DB::table('comprobantes')
                            
                            ->get();


                // dd($articulos);

                foreach ($articulos as $key => $value) {

                    // dump('id: '.$value->id .' creado: '. $value->created_at .' updated '.$value->updated_at);

                    DB::table('producto_comprobantes')
                    ->where('comprobante_id', $value->id)
                    ->update(['created_at' => $value->created_at,
                                'updated_At'=> $value->updated_at
                
                    ]);

                    // dump($res);

                    // $p = Proveedor::updateOrCreate(
                    //     ['nombre' => $value->proveedor, ],
                    //     ['empresa_id' => Auth::user()->empresa_id,]
                    // );


                }

                
            });
        

        });

        // DEJA ENTRAR A LOS ADMIN PLUS 4 Y SUPER 3
        Route::middleware([ControlDeRolAdminPlus::class])->group(function () {
            Route::get('/adminPlus', function () {
               
                return Auth::user();
            });
            
            Route::get('/usuarios', Usuarios::class)->name('usuarios');
            Route::get('/updateUsuario/{id}', Update::class)->name('updateUsuario');
            Route::get('/ordenCompra', VerOrdenCompra::class)->name('ordenCompra');
            Route::get('/imprimirOrdenCompra',[ImprimirOrdenCompra::class,'imprimirOrdenCompra'])->name('imprimirOrdenCompra');
            Route::get('/reImprimirOrdenCompra/{id}',[ImprimirOrdenCompra::class,'reImprimirOrdenCompra'])->name('reImprimirOrdenCompra');


            Route::get('/historialOrdenCompra',HistorialOrdenCompra::class)->name('historialOrdenCompra');


            


        

        });
 
        Route::middleware([ControlDeRolAdmin::class])->group(function () {
            Route::get('/admin', function () {
               
                return Auth::user();
            });

            Route::get('/inventario', VerInventario::class)->name('inventario');
            Route::get('/inventario/nuevo/{id?}', NuevoArticulo::class)->name('inventario.nuevo');
            Route::get('/importarInventario', ImportarInventario::class)->name('importarInventario');
            Route::get('/edicionMultiple', EdicionMultiple::class)->name('edicionMultiple');
            Route::get('/reporteEdicionMultiple', [InventarioController::class, 'remporteEdicionMultiple'])->name('reporteEdicionMultiple');
            Route::get('/proveedor', VerProveedor::class)->name('proveedor');


            Route::get('/configuracion/basico', Basico::class)->name('configuracionbasico');

            Route::get('/preciosPublico', PreciosPublico::class)->name('preciosPublico');


            Route::get('/stock', VerStock::class)->name('stock');


        });





        Route::get('/panel', Panel::class)->name('panel');
        Route::view('/profile', 'profile')->name('profile');        
    
        Route::get('/remitoscomprobante', VerRemito::class)->name('remitoscomprobante');

        Route::get('/comprobante', VerComprobante::class)->name('comprobante');
        Route::get('/notacredito/{comprobante}', NotaCredito::class)->name('notacredito');

        Route::get('/productosComprobante/{idComprobante}', ProductosComprobante::class)->name('productosComprobante');
        Route::get('/nuevoComprobante', NuevoComprobante::class)->name('nuevoComprobante');
        Route::get('/venta', Venta::class)->name('venta');
        Route::get('/recibirstock', RecibirStock::class)->name('recibirstock');
        Route::get('/historicoenvio', HistoricoEnvio::class)->name('historicoenvio');
        Route::get('/movimientostock/{stock_id?}', MovimientoStock::class)->name('movimientostock');
        Route::get('/importarstock', ImportarStock::class)->name('importarstock');
        Route::get('/cliente', VerCliente::class)->name('cliente');
        Route::get('/cuentaCorriente/{cliente}', CuentaCorriente::class)->name('cuentaCorriente');
        Route::get('/codigoBarra', CodigoBarra::class)->name('codigoBarra');


        Route::get('/reciboPdf/{recibo_id?}', [ReciboPdfController::class, 'imprimir'])->name('reciboPdf');
        Route::get('/codigoBarraPdf', [CodigoDeBarraController::class, 'imprimir'])->name('codigoBarraPdf');
        


        // RUTAS DE LOS REPORTES 
        // Route::get('/reporteVentaUsuario', [ReporteVentaUsuarioController::class, 'imprimir'])->name('reporteVentaUsuario');
        // Route::get('/reporteVentaUsuarioCompleto', [ReporteVentaUsuarioController::class, 'reporteCompleto'])->name('reporteVentaUsuarioCompleto');
        // ESTA ES LA MISMA RUTA CON POST
        Route::post('/reporteVentaUsuario', [ReporteVentaUsuarioController::class, 'imprimir'])->name('reporteVentaUsuario');
        Route::post('/reporteVentaUsuarioCompleto', [ReporteVentaUsuarioController::class, 'reporteCompleto'])->name('reporteVentaUsuarioCompleto');




        Route::get('/reportes', Reportes::class)->name('reportes');


        


        Route::get('/presupuesto', VerPresupuesto::class)->name('presupuesto');

        Route::get('/presupuesto/base64/{presupuesto_id}/{formato?}', [PresupuestoController::class, 'presupuestoBase64'])->name('presupuesto.base64');

        Route::get('/novedades', Novedades::class)->name('novedades');

        Route::get('/facturarRemito/{idComprobante}', FacturarRemito::class)->name('facturarRemito');


        Route::get('/verMesas', VerMesas::class)->name('verMesas');
        Route::get('/modificarMesa/{mesa}', ModificarMesa::class)->name('modificarMesa');
        Route::get('/comandas', Comandas::class)->name('comandas');
        Route::get('/imprimirComanda/{comanda}', [ImprimirComandaController::class, 'imprimir'])->name('imprimirComanda');
        Route::get('/imprimirMesa/{mesa}', [ImprimirMesaController::class, 'imprimir'])->name('imprimirMesa');


        Route::get('/ventasArticulos', VerVentasArticulos::class)->name('ventasArticulos');

        Route::get('/gasto', VerGasto::class)->name('gasto');






        // crear una ruta metodo post con una funcion    
        Route::post('/rutaEnviarPDF', function (Request $request) {
            // Procesar los datos del formulario
            $datos = $request->all();
            // return response()->json($datos);
            // Realizar alguna acción con los datos
            // return response()->json(['mensaje' => 'Formulario procesado correctamente', 'datos' => $datos]);

                $nombreCliente = '';
                $formatoComprobante  = $datos['formato'];

                if ($datos['tipo'] == 'presupuesto'){
                    $mensaje = 'Hola '. $nombreCliente .'! Te enviamos tu Presupuesto. Gracias por elegirnos!. Enviado con *https://llfactura.com*';
                }else{
                    $mensaje = 'Hola '. $nombreCliente .'! Te enviamos tu comprobante. Gracias por elegirnos!. Enviado con *https://llfactura.com*';
                }


                EnviarPdfComprobanteJob::dispatch(
                    $datos['tipo'],
                    $datos['comprobante_id'],
                    $formatoComprobante,
                    $nombreCliente,
                    $datos['telefono'],
                    $mensaje, 
                    Auth::user()->id
                );

                // redirigir ruta a la ruta de origen 
                return redirect()->back()->with('mensaje', 'El comprobante se envio correctamente a: '. $datos['telefono']);


        })->name('rutaEnviarPDF');






        Route::get('formatoPDF/{tipo}/{comprobante_id?}', function ($tipo,$comprobante_id = null) {

            if($comprobante_id){

                $empresa= Empresa::find(Auth::user()->empresa_id);

                return view('comprobante.formatoPDF',['comprobante_id'=>$comprobante_id,
                                                        'tipo'=>$tipo,'formato'=>$empresa->formatoImprecion])->render();               

            }else{
                return redirect('comprobante')->with('mensaje', 'Sin elementos para mostrar factura');
            }
        })->name('formatoPDF');



        Route::get('/imprimirComprobante/{comprobante_id?}/{formato?}', [ComprobanteController::class, 'imprimir'])->name('imprimirComprobante');
        Route::get('/imprimirPresupuesto/{presupuesto_id?}/{formato?}', [PresupuestoController::class, 'imprimir'])->name('imprimirPresupuesto');

        // PARA TESTING
        // Route::get('/obtenerPdfBase64/{comprobante_id?}/{formato?}', [ComprobanteController::class, 'obtenerPdfBase64'])->name('obtenerPdfBase64');


        //ruta para mejorar CARGA UN COMPROBANTE AL CARRITO
        Route::get('/cargarComprobante/{comp}', [CargarComprobanteController::class, 'cargar'])->name('cargarComprobante');

        //RUTA CIERRE DE CAJA
        Route::get('/cierrecaja', VerCierreCaja::class)->name('cierrecaja');

        Route::get('/galeriaImagenes', CloudinaryListarImagenes::class)->name('galeriaImagenes');




    });


    

require __DIR__.'/auth.php';
