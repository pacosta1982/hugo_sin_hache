<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Employee;
use App\Models\Product;
use App\Services\PointsService;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Requests\AdminOrdersFilterRequest;

class AdminController extends Controller
{
    public function __construct(
        private PointsService $pointsService
    ) {}

    public function orders(Request $request)
    {
        $query = Order::with(['employee', 'product']);

        if ($request->filled('status')) {
            $query->where('estado', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('fecha', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('fecha', '<=', $request->date_to);
        }

        // Search by employee name or product name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('empleado_nombre', 'like', "%{$search}%")
                  ->orWhere('producto_nombre', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('fecha', 'desc')->paginate(25);

        // Calculate summary statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('estado', Order::STATUS_PENDING)->count(),
            'processing_orders' => Order::where('estado', Order::STATUS_PROCESSING)->count(),
            'completed_orders' => Order::where('estado', Order::STATUS_COMPLETED)->count(),
            'cancelled_orders' => Order::where('estado', Order::STATUS_CANCELLED)->count(),
            'total_points_redeemed' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])->sum('puntos_utilizados'),
        ];

        return view('admin.orders', compact('orders', 'stats'));
    }

    public function updateOrderStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        try {
            $result = $this->pointsService->updateOrderStatus(
                $order, 
                $request->validated('status'), 
                $request->validated('notes')
            );

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

    public function employees(Request $request)
    {
        $query = Employee::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('rol_usuario', $request->role);
        }

        $employees = $query->orderBy('nombre')->paginate(25);

        // Calculate summary statistics
        $stats = [
            'total_employees' => Employee::count(),
            'total_points_distributed' => Employee::sum('puntos_totales'),
            'total_points_redeemed' => Employee::sum('puntos_canjeados'),
            'admin_count' => Employee::where('rol_usuario', 'Administrador')->count(),
        ];

        return view('admin.employees', compact('employees', 'stats'));
    }

    public function products(Request $request)
    {
        $query = Product::query();

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('categoria', $request->category);
        }

        // Filter by active status
        if ($request->filled('active')) {
            $query->where('activo', $request->active === '1');
        }

        $products = $query->orderBy('nombre')->paginate(25);

        // Get categories for filter
        $categories = Product::whereNotNull('categoria')->distinct()->pluck('categoria')->sort();

        // Calculate summary statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('activo', true)->count(),
            'out_of_stock' => Product::where('activo', true)->where('stock', 0)->count(),
            'average_cost' => (int) Product::where('activo', true)->avg('costo_puntos'),
        ];

        return view('admin.products', compact('products', 'categories', 'stats'));
    }

    public function reports(Request $request)
    {
        // This would contain various reports and analytics
        // For now, just return a basic view
        return view('admin.reports');
    }

    // API Methods
    public function apiOrders(AdminOrdersFilterRequest $request)
    {
        $query = Order::with(['employee', 'product']);

        // Apply filters (reuse the same logic as orders method)
        if ($request->filled('status')) {
            $query->where('estado', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('fecha', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('fecha', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('empleado_nombre', 'like', "%{$search}%")
                  ->orWhere('producto_nombre', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('fecha', 'desc')->paginate(25);

        return response()->json([
            'success' => true,
            'orders' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    public function apiEmployees(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('rol_usuario', $request->role);
        }

        $employees = $query->orderBy('nombre')->paginate(25);

        return response()->json([
            'success' => true,
            'employees' => $employees->items(),
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'total' => $employees->total(),
            ]
        ]);
    }

    public function apiProducts(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('categoria', $request->category);
        }

        if ($request->filled('active')) {
            $query->where('activo', $request->active === '1');
        }

        $products = $query->orderBy('nombre')->paginate(25);

        return response()->json([
            'success' => true,
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    public function apiReports(Request $request)
    {
        // Generate basic analytics data
        $stats = [
            'orders' => [
                'total' => Order::count(),
                'this_month' => Order::whereMonth('created_at', now()->month)->count(),
                'pending' => Order::where('estado', Order::STATUS_PENDING)->count(),
                'completed' => Order::where('estado', Order::STATUS_COMPLETED)->count(),
            ],
            'employees' => [
                'total' => Employee::count(),
                'active_this_month' => Employee::whereHas('orders', function ($q) {
                    $q->whereMonth('created_at', now()->month);
                })->count(),
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('activo', true)->count(),
            ],
            'points' => [
                'total_redeemed' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])->sum('puntos_utilizados'),
                'this_month' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])
                    ->whereMonth('created_at', now()->month)
                    ->sum('puntos_utilizados'),
            ]
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}