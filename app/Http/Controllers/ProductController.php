<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\PointsService;
use App\Services\NotificationService;
use App\Services\ProductRecommendationService;
use App\Http\Requests\ProductRedemptionRequest;

class ProductController extends Controller
{
    public function __construct(
        private PointsService $pointsService,
        private NotificationService $notificationService,
        private ProductRecommendationService $recommendationService
    ) {}

    public function index(Request $request)
    {
        return view('products.index');
    }

    public function show(Request $request, Product $product)
    {
        $employee = $request->get('employee');
        $canAfford = $employee ? $this->pointsService->canAffordProduct($employee, $product) : false;
        
        $isFavorite = false;
        
        return view('products.show', compact('product', 'employee', 'canAfford', 'isFavorite'));
    }

    public function redeem(ProductRedemptionRequest $request, Product $product)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        try {
            $result = $this->pointsService->redeemProduct($employee, $product, [
                'observaciones' => $request->validated('observaciones')
            ]);

            if ($result['success']) {
                try {
                    $this->notificationService->sendOrderNotifications($result['order']);
                } catch (\Exception $e) {
                    \Log::warning('Failed to send notifications for order', [
                        'order_id' => $result['order']->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json($result);
            }

            return back()->with('success', $result['message']);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function getRecommendations(Request $request)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $limit = $request->query('limit', 6);
        $recommendations = $this->recommendationService
            ->getRecommendationsForEmployee($employee->id_empleado, $limit);

        return response()->json([
            'success' => true,
            'recommendations' => $recommendations->map(function ($product) use ($employee) {
                return [
                    'id' => $product->id,
                    'nombre' => $product->nombre,
                    'descripcion' => $product->descripcion,
                    'puntos_requeridos' => $product->puntos_requeridos,
                    'stock' => $product->stock,
                    'stock_status' => $product->stock_status,
                    'is_available' => $product->is_available,
                    'imagen_url' => $product->imagen_url,
                    'recommendation_reason' => $this->recommendationService
                        ->getRecommendationReasons($product, $employee)
                ];
            })
        ]);
    }

    public function getCategories(Request $request)
    {
        $categories = ProductCategory::active()
            ->roots()
            ->with(['children' => function ($query) {
                $query->active()->ordered()->withCount('products');
            }])
            ->withCount(['products' => function ($query) {
                $query->where('activo', true);
            }])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'color' => $category->color,
                    'products_count' => $category->products_count,
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'icon' => $child->icon,
                            'color' => $child->color,
                            'products_count' => $child->products_count,
                        ];
                    })
                ];
            })
        ]);
    }

    public function getCategoryRecommendations(Request $request, ProductCategory $category)
    {
        $employee = $request->get('employee');
        $limit = $request->query('limit', 6);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }


        $recommendations = $this->recommendationService
            ->getCategoryRecommendations($employee->id_empleado, $category->id, $limit);

        return response()->json([
            'success' => true,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'icon' => $category->icon,
                'color' => $category->color,
            ],
            'recommendations' => $recommendations->map(function ($product) use ($employee) {
                return [
                    'id' => $product->id,
                    'nombre' => $product->nombre,
                    'descripcion' => $product->descripcion,
                    'puntos_requeridos' => $product->puntos_requeridos,
                    'stock' => $product->stock,
                    'stock_status' => $product->stock_status,
                    'is_available' => $product->is_available,
                    'imagen_url' => $product->imagen_url,
                    'category' => $product->category ? [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'color' => $product->category->color,
                        'icon' => $product->category->icon,
                    ] : null,
                ];
            })
        ]);
    }
}