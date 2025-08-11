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

    public function dashboard(Request $request)
    {

        $stats = [
            'orders' => [
                'total' => Order::count(),
                'today' => Order::whereDate('created_at', today())->count(),
                'this_week' => Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'this_month' => Order::whereMonth('created_at', now()->month)->count(),
                'pending' => Order::where('estado', Order::STATUS_PENDING)->count(),
                'processing' => Order::where('estado', Order::STATUS_PROCESSING)->count(),
                'completed' => Order::where('estado', Order::STATUS_COMPLETED)->count(),
                'cancelled' => Order::where('estado', Order::STATUS_CANCELLED)->count(),
            ],
            'employees' => [
                'total' => Employee::count(),
                'active_this_month' => Employee::whereHas('orders', function ($q) {
                    $q->whereMonth('created_at', now()->month);
                })->count(),
                'total_points_distributed' => Employee::sum('puntos_totales'),
                'total_points_available' => Employee::sum('puntos_disponibles'),
                'total_points_redeemed' => Employee::sum('puntos_canjeados'),
                'admin_count' => Employee::where('rol_usuario', 'Administrador')->count(),
            ],
            'products' => [
                'total' => Product::count(),
                'active' => Product::where('activo', true)->count(),
                'inactive' => Product::where('activo', false)->count(),
                'out_of_stock' => Product::where('activo', true)->where('stock', 0)->count(),
                'low_stock' => Product::where('activo', true)->where('stock', '>', 0)->where('stock', '<=', 5)->count(),
                'average_cost' => (int) Product::where('activo', true)->avg('costo_puntos'),
                'total_value' => Product::where('activo', true)->sum('costo_puntos'),
            ],
            'points' => [
                'total_redeemed' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])->sum('puntos_utilizados'),
                'today_redeemed' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])
                    ->whereDate('created_at', today())
                    ->sum('puntos_utilizados'),
                'this_week_redeemed' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->sum('puntos_utilizados'),
                'this_month_redeemed' => Order::whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING])
                    ->whereMonth('created_at', now()->month)
                    ->sum('puntos_utilizados'),
            ]
        ];


        $recentOrders = Order::with(['employee', 'product'])
            ->latest()
            ->limit(10)
            ->get();


        $topEmployees = Employee::withCount(['orders as total_orders'])
            ->withSum(['orders as points_redeemed' => function ($query) {
                $query->whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING]);
            }], 'puntos_utilizados')
            ->orderBy('points_redeemed', 'desc')
            ->limit(10)
            ->get();


        $popularProducts = Product::withCount(['orders as total_orders'])
            ->withSum(['orders as points_earned' => function ($query) {
                $query->whereIn('estado', [Order::STATUS_COMPLETED, Order::STATUS_PROCESSING]);
            }], 'puntos_utilizados')
            ->orderBy('total_orders', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topEmployees', 'popularProducts'));
    }

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


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('empleado_nombre', 'like', "%{$search}%")
                  ->orWhere('producto_nombre', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('fecha', 'desc')->paginate(25);


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


        $categories = Product::whereNotNull('categoria')->distinct()->pluck('categoria')->sort();


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


        return view('admin.reports');
    }

    public function points(Request $request)
    {
        return view('admin.points');
    }


    public function apiOrders(AdminOrdersFilterRequest $request)
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