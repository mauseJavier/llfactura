<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Presupuesto;
use App\Models\ProductoPresupuesto;

use App\Events\NovedadCreada;



//colocar el use auth
use Illuminate\Support\Facades\Auth;

use App\Models\Cliente;


// {
//     "tipoComprobante": "Presupuesto",
//     "total": 1500.50,
//     "tipoDocumento": "DNI",
//     "cuit": "20304050607",
//     "razonSocial": "Juan Mause",
//     "domicilio": "Calle Falsa 123",
//     "correoCliente": "juan.perez@example.com",
//     "tipoContribuyente": "Responsable Inscripto",
//     "idEmpresa": 1,

//     "carrito": [
//         {
//             "codigo": "001",
//             "detalle": "Producto Mause",
//             "porcentaje": 10,
//             "precioLista": 100,
//             "descuento": 5,
//             "precio": 95,
//             "costo": 80,
//             "iva": 21,
//             "cantidad": 2,
//             "rubro": "Electrónica",
//             "proveedor": "Proveedor A",
//             "marca": "Marca A",
//             "controlStock": true
//         },
//         {
//             "codigo": "002",
//             "detalle": "Producto B",
//             "porcentaje": 15,
//             "precioLista": 200,
//             "descuento": 10,
//             "precio": 180,
//             "costo": 150,
//             "iva": 21,
//             "cantidad": 1,
//             "rubro": "Hogar",
//             "proveedor": "Proveedor B",
//             "marca": "Marca B",
//             "controlStock": false
//         }
//     ]
// }

class ApiPresupuestoController extends Controller
{
    //
    public function store(Request $request)
    {



        $validated = $request->validate([
            'tipoComprobante' => 'required|string',
            'total' => 'required|numeric',
            'tipoDocumento' => 'required|string',
            'cuit' => 'required|string',
            'razonSocial' => 'required|string',
            'domicilio' => 'required|string',
            'correoCliente' => 'nullable|email',
            'tipoContribuyente' => 'required|string',
            'carrito' => 'required|array',
            'idEmpresa' => 'required|integer',
        ]);

        $descripcionTipoComp = 'Presupuesto';

        $ultimoRegistro = Presupuesto::where('empresa_id', $validated['idEmpresa'])->latest()->first();
        $ultimoId = $ultimoRegistro ? $ultimoRegistro->id + 1 : 1;

        $presupuestoGuardado = Presupuesto::create([
            'tipoComp' => $validated['tipoComprobante'],
            'numero' => $ultimoId,
            'total' => round($validated['total'], 2),
            'fechaVencimiento' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'DocTipo' => $validated['tipoDocumento'],
            'cuitCliente' => $validated['cuit'],
            'razonSocial' => $validated['razonSocial'],
            'tipoContribuyente' => $validated['tipoContribuyente'],
            'domicilio' => $validated['domicilio'],
            'empresa_id' => $validated['idEmpresa'],
            'fecha' => now()->format('Y-m-d H:i:s'),
            'leyenda' => $request->leyenda ?? '',
            'idFormaPago' => $request->idFormaPago ?? 1,
            'deposito_id' => Auth::user()->deposito_id,
            'usuario' => Auth::user()->name,
        ]);

        Cliente::updateOrCreate(
            ['numeroDocumento' => $validated['cuit'], 'empresa_id' => $validated['idEmpresa']],
            [
                'tipoDocumento' => trim($validated['tipoDocumento']),
                'numeroDocumento' => trim($validated['cuit']),
                'razonSocial' => trim($validated['razonSocial']),
                'domicilio' => trim($validated['domicilio']),
                'correo' => trim($validated['correoCliente']),
                'tipoContribuyente' => trim($validated['tipoContribuyente']),
            ]
        );

        if (isset($validated['carrito'])) {
            foreach ($validated['carrito'] as $value) {
                ProductoPresupuesto::create([
                    'presupuesto_id' => $presupuestoGuardado->id,
                    'presupuesto_numero' => $presupuestoGuardado->numero,
                    'codigo' => $value['codigo'],
                    'detalle' => $value['detalle'],
                    'porcentaje' => $value['porcentaje'],
                    'precioLista' => $value['precioLista'],
                    'descuento' => $value['descuento'],
                    'precio' => $value['precio'],
                    'costo' => $value['costo'],
                    'iva' => $value['iva'],
                    'cantidad' => $value['cantidad'],
                    'rubro' => $value['rubro'],
                    'proveedor' => $value['proveedor'],
                    'marca' => $value['marca'],
                    'controlStock' => $value['controlStock'],
                    'tipoComp' => $validated['tipoComprobante'],
                    'fecha' => $presupuestoGuardado->fecha,
                    'idFormaPago' => $request->idFormaPago ?? 1,
                    'usuario' => Auth::user()->name,
                    'empresa_id' => $validated['idEmpresa'],
                ]);
            }
        }

                // Disparar el evento con solo el detalle
            // Disparar el evento con todos los datos necesarios
            NovedadCreada::dispatch(
                'Nuevo Presupuesto',          // Título
                'Nuevo Presupuesto '. $presupuestoGuardado->razonSocial .' Total: $' .$presupuestoGuardado->total, // Detalle
                'presupuesto',                 // Nombre de la ruta
                '',          // URL
                'Nuevo Presupuesto',  // Pie
                $presupuestoGuardado->usuario ?? 'Sistema', // Usuario
                $presupuestoGuardado->empresa_id ?? 1    // ID de la empresa
            );

        return response()->json([
            'success' => true,
            'data' => $presupuestoGuardado,
        ]);
    }

    function guardarPresupuesto(){

        $descripcionTipoComp = 'Presupuesto';

        // Obtener el último registro
        $ultimoRegistro = Presupuesto::where('empresa_id',$this->empresa->id)->latest()->first();

        if ($ultimoRegistro) {
            $ultimoId = $ultimoRegistro->id + 1;
            // echo "El último ID es: " . $ultimoId;
        } else {
            // echo "No hay registros en la tabla.";
            $ultimoId =  1;
        }

            // Post::create($validated);
            $presupuestoGuardado = Presupuesto::create([
                
                'tipoComp'=>$this->tipoComprobante,
                'numero' => $ultimoId,
                'total' => round($this->total,2),
                
                'fechaVencimiento' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s') ,// vencimiento del presupuesto 7 dias 
                'DocTipo'=>$this->tipoDocumento,
                'cuitCliente' => $this->cuit,
                'razonSocial'=>$this->razonSocial,
                'tipoContribuyente'=>$this->tipoContribuyente,
                'domicilio'=>$this->domicilio,
                'empresa_id'=> $this->empresa->id,
                'fecha'=> Carbon::now()->format('Y-m-d H:i:s'),
                'leyenda'=> $this->leyenda,
                'idFormaPago'=>$this->idFormaPago,
                
                'deposito_id'=>$this->usuario->deposito_id,
                'usuario'=> $this->usuario->name,
            ]);


            $cliente = Cliente::updateOrCreate(
                ['numeroDocumento'=>$this->cuit,'empresa_id'=> $this->empresa->id],
                [            
                'tipoDocumento'=>trim($this->tipoDocumento),
                'numeroDocumento'=>trim($this->cuit),
                'razonSocial'=>trim($this->razonSocial),
                'domicilio'=>trim($this->domicilio),
                'correo'=>trim($this->correoCliente),
                'tipoContribuyente'=>trim($this->tipoContribuyente)]
            );

            // dd($this->carrito['carrito']);

            if(isset($this->carrito['carrito'])){//SI EXITE GUARDA LOS ARTICULOS
                
                foreach ($this->carrito['carrito'] as $key => $value) {
                    // array:6 [▼ // app/Livewire/Factura/NuevoComprobante.php:75
                    // "codigo" => "28464334"
                    // "detalle" => "alfajor"
                    // "precio" => 1
                    // "iva" => 21
                    // "cantidad" => 1
                    // "subtotal" => 1
                    // ]
                    // dump($value);
                    ProductoPresupuesto::create([
                        'presupuesto_id'=> $presupuestoGuardado->id,
                        'presupuesto_numero'=>$presupuestoGuardado->numero,
                        'codigo'=>$value['codigo'],
                        'detalle'=>$value['detalle'],

                        'porcentaje'=> $value['porcentaje'],
                        'precioLista'=> $value['precioLista'] ,
                        'descuento'=> $value['descuento'] ,
                        'precio'=>$value['precio'],

                        'costo'=>$value['costo'],


                        'iva'=>$value['iva'],
                        'cantidad'=>$value['cantidad'],
                        'rubro'=>$value['rubro'],
                        'proveedor'=>$value['proveedor'],
                        'marca'=>$value['marca'],

                        'controlStock'=>$value['controlStock'],
                        'tipoComp'=>$this->tipoComprobante,
                        'fecha'=>$presupuestoGuardado->fecha,
                        'idFormaPago'=>$this->idFormaPago,
                        
                        'usuario'=> $this->usuario->name,
                        'empresa_id'=> $this->empresa->id,
                    ]);


                    //UN PRESUPUESTO NO DESCUENTA STOCK
                }
            }

            return $presupuestoGuardado->id;
          
    }
}


