<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;

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
            return response()->json([
                'success' => false,
                'message' => 'Debe enviar el parámetro q para buscar por detalle o código. Ejemplo: /api/inventarios/123/buscar?q=producto&cat=ropa'
            ], 400);
        }
        $articulos = Inventario::where('empresa_id', $empresa_id)
            ->where('publicarTienda', true)

            ->when($query, function($q) use ($query) {
                $q->where('detalle', 'like', "%$query%")
                  ->orWhere('codigo', 'like', "%$query%")
                  ;
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
}
