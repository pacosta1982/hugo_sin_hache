<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Favorite;
use App\Services\FavoritesService;

class FavoriteController extends Controller
{
    public function __construct(
        private FavoritesService $favoritesService
    ) {}

    public function index(Request $request)
    {
        $employee = $request->get('employee');
        
        \Log::info('Favorites WEB index called', [
            'has_employee' => $employee ? 'yes' : 'no',
            'employee_id' => $employee ? $employee->id_empleado : 'none',
            'has_auth_header' => $request->hasHeader('Authorization'),
            'user_agent' => $request->userAgent(),
        ]);
        
        $favorites = $employee ? $this->favoritesService->getUserFavorites($employee) : collect();
        $stats = $employee ? $this->favoritesService->getFavoritesStats($employee) : [
            'total_favorites' => 0,
            'available_favorites' => 0,
            'total_cost' => 0,
            'affordable_favorites' => 0,
            'can_afford_all' => true,
        ];

        \Log::info('Favorites WEB index result', [
            'has_employee' => $employee ? 'yes' : 'no',
            'favorites_count' => $favorites->count(),
            'stats' => $stats,
        ]);

        return view('favorites.index', compact('favorites', 'stats', 'employee'));
    }

    public function apiIndex(Request $request)
    {
        $employee = $request->get('employee');
        
        \Log::info('Favorites API index called', [
            'has_employee' => $employee ? 'yes' : 'no',
            'employee_id' => $employee ? $employee->id_empleado : 'none',
        ]);
        
        if (!$employee) {
            \Log::warning('Favorites API index: No employee found');
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $favorites = $this->favoritesService->getUserFavorites($employee);
        $stats = $this->favoritesService->getFavoritesStats($employee);

        \Log::info('Favorites API index result', [
            'employee_id' => $employee->id_empleado,
            'favorites_count' => $favorites->count(),
            'stats' => $stats,
        ]);

        return response()->json([
            'success' => true,
            'favorites' => $favorites,
            'stats' => $stats
        ]);
    }

    public function toggle(Request $request, Product $product)
    {
        $employee = $request->get('employee');
        
        \Log::info('Favorites API toggle called', [
            'product_id' => $product->id,
            'employee_id' => $employee ? $employee->id_empleado : 'none',
            'employee_exists' => $employee ? 'yes' : 'no',
        ]);
        
        if (!$employee) {
            \Log::warning('Favorites API toggle: No employee found');
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        try {
            $result = $this->favoritesService->toggleFavorite($employee, $product);

            \Log::info('Favorites API toggle result', [
                'employee_id' => $employee->id_empleado,
                'product_id' => $product->id,
                'added' => $result['added'],
                'result' => $result,
            ]);

            $message = $result['added'] ? 'Producto agregado a favoritos' : 'Producto removido de favoritos';
            $type = $result['added'] ? 'success' : 'info';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'added' => $result['added'],
                    'message' => $message
                ]);
            }

            return back()->with($type, $message);

        } catch (\Exception $e) {
            \Log::error('Favorites API toggle error', [
                'employee_id' => $employee ? $employee->id_empleado : 'none',
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->with('error', 'Error al actualizar favoritos');
        }
    }

    public function destroy(Request $request, Favorite $favorite)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        if ($favorite->empleado_id !== $employee->id_empleado) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        try {
            $favorite->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto removido de favoritos'
                ]);
            }

            return back()->with('success', 'Producto removido de favoritos');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al remover de favoritos'
                ], 400);
            }

            return back()->with('error', 'Error al remover de favoritos');
        }
    }
}