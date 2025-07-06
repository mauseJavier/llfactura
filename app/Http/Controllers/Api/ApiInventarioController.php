<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Empresa;

class ApiInventarioController extends Controller
{
    // Devuelve inventarios filtrados por empresa_id recibido en la ruta
    public function index(Request $request, $empresa_id = null)
    {
        if (!$empresa_id) {
            return response()->json([
                'success' => false,
                'message' => 'Debe indicar el parámetro empresa_id en la ruta. Ejemplo: /api/inventarios/123?per_page=10&page=1.\nOpcionalmente puede agregar per_page y page para paginación.'
            ], 400);
        }

        $perPage = $request->query('per_page');
        $page = $request->query('page');

        $query = Inventario::where('empresa_id', $empresa_id)->where('publicarTienda', true)
            ->orderByDesc('updated_at');

        $parseImagen = function ($item) {
            $itemArray = $item->toArray();
            unset($itemArray['costo']); // Ocultar costo
            $itemArray['imagen'] = $item->imagen ? json_decode($item->imagen, true) : null;
            return $itemArray;
        };

        if ($perPage && $page) {
            $inventarios = $query->paginate((int)$perPage, ['*'], 'page', (int)$page);
            $data = collect($inventarios->items())->map($parseImagen)->all();
            return response()->json([
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => $inventarios->currentPage(),
                    'per_page' => $inventarios->perPage(),
                    'total' => $inventarios->total(),
                    'last_page' => $inventarios->lastPage(),
                ]
            ]);
        } else {
            $inventarios = $query->get();
            $data = $inventarios->map($parseImagen);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }
    }

    /**
     * Buscar artículos por detalle o código para una empresa
     * @param \Illuminate\Http\Request $request
     * @param int $empresa_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar(Request $request, $empresa_id)
    {
        $query = $request->input('q');
        $rubro = $request->input('cat');

        if (!$query AND !$rubro ) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Debe enviar el parámetro q para buscar por detalle o código. Ejemplo: /api/inventarios/123/buscar?q=producto&cat=ropa'
            // ], 400);

            return $this->index($request, $empresa_id);
        }

        
        // $articulos = Inventario::where('empresa_id', $empresa_id)

        //     ->where('publicarTienda', true)

        //     ->when($query, function($q) use ($query) {
        //         $q->where('detalle', 'like', "%$query%")
        //           ->orWhere('codigo', 'like', "%$query%")
        //           ;
        //     })
        //     ->when($rubro, function($q) use ($rubro) {
        //         $q->where('rubro', 'like', "%$rubro%");
        //     })

        //     ->orderByDesc('updated_at')
        //     ->limit(30)
        //     ->get();

        $articulos = Inventario::where('empresa_id', $empresa_id)
            ->where('publicarTienda', true)
            ->when($query, function($q) use ($query) {
                $q->where(function($subQ) use ($query) {
                    $subQ->where('detalle', 'like', "%$query%")
                        ->orWhere('codigo', 'like', "%$query%")
                        ->orWhere('rubro', 'like', "%$query%");

                });
            })
            ->when($rubro, function($q) use ($rubro) {
                $q->where('rubro', 'like', "%$rubro%");
            })
            ->orderByDesc('updated_at')
            ->limit(30)
            ->get();

            

        // Decodificar campo imagen si existe
        $articulos = $articulos->map(function($item) {
            $itemArray = $item->toArray();
            unset($itemArray['costo']); // Ocultar costo
            $itemArray['imagen'] = $item->imagen ? json_decode($item->imagen, true) : null;
            return $itemArray;
        });
        return response()->json([
            'success' => true,
            'data' => $articulos
        ]);
    }

    /**
     * Devuelve los rubros únicos de una empresa
     * @param int $empresa_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function rubros($empresa_id = null)
    {
        if (!$empresa_id) {
            return response()->json([
                'success' => false,
                'message' => 'Debe indicar el parámetro empresa_id en la ruta. Ejemplo: /api/rubros/123'
            ], 400);
        }

        $rubros = \App\Models\Rubro::where('empresa_id', $empresa_id)
            ->select('nombre')
            ->distinct()
            ->orderBy('nombre', 'asc')
            ->get()
            ->pluck('nombre');

        return response()->json([
            'success' => true,
            'data' => $rubros
        ]);
    }

    /**
     * Devuelve un artículo específico por empresa e id
     * @param int $empresa_id
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verArticulo($empresa_id, $id)
    {
        $articulo = \App\Models\Inventario::where('empresa_id', $empresa_id)
            ->where('id', $id)
            ->first();

        if (!$articulo) {
            return response()->json([
                'success' => false,
                'message' => 'Artículo no encontrado'
            ], 404);
        }

        $itemArray = $articulo->toArray();
        unset($itemArray['costo']); // Ocultar costo
        $itemArray['imagen'] = $articulo->imagen ? json_decode($articulo->imagen, true) : null;

        // Obtener el último registro de stock por cada depósito para el código del artículo
        $stocks = \App\Models\Stock::where('stocks.codigo', $articulo->codigo)
            ->where('stocks.empresa_id', $empresa_id)
            ->join('depositos', 'stocks.deposito_id', '=', 'depositos.id')
            ->select('stocks.*','stocks.saldo as saldo', 'depositos.nombre as deposito_nombre')
            ->orderBy('stocks.deposito_id')
            ->orderByDesc('stocks.id')
            ->get()
            ->unique('deposito_id')
            ->values();

        $itemArray['stocks'] = $stocks->map(function($stock) {
            return [
                'deposito_id' => $stock->deposito_id,
                'deposito_nombre' => $stock->deposito_nombre,
                'stock' => $stock->saldo,
                'detalle' => $stock->detalle,
                'updated_at' => $stock->updated_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $itemArray
        ]);
    }

    /**
     * Devuelve artículos de una empresa, permite filtrar por destacados y limitar la cantidad
     * @param \Illuminate\Http\Request $request
     * @param int $empresa_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function articulos(Request $request, $empresa_id)
    {
        $destacados = $request->query('destacados');
        $limit = $request->query('limit', 10);

        $query = Inventario::where('empresa_id', $empresa_id)
            ->where('publicarTienda', true);

        if ($destacados) {
            $query->where('articuloDestacado', true);
        }

        $query->orderByDesc('updated_at');

        $articulos = $query->limit($limit)->get();

        $articulos = $articulos->map(function($item) {
            $itemArray = $item->toArray();
            unset($itemArray['costo']); // Ocultar costo
            $itemArray['imagen'] = $item->imagen ? json_decode($item->imagen, true) : null;
            return $itemArray;
        });

        return response()->json([
            'success' => true,
            'data' => $articulos
        ]);
    }

    /**
     * Devuelve los datos de la empresa por ID
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function empresa($id)
    {
        $empresa = Empresa::select('razonSocial','telefono','correo','domicilio')
            ->where('id', $id)
            ->first();

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $empresa
        ]);
    }
}
