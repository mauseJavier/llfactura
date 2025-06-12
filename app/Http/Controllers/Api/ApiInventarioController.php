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

        $query = Inventario::where('empresa_id', $empresa_id);

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
}
