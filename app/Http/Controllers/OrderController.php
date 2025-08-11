<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PointsService;

class OrderController extends Controller
{
    public function __construct(
        private PointsService $pointsService
    ) {}

    public function history(Request $request)
    {
        $employee = $request->get('employee');
        $orders = $employee ? Order::where('empleado_id', $employee->id_empleado)
            ->with('product')
            ->orderBy('fecha', 'desc')
            ->paginate(20) : collect();
        $stats = $employee ? $this->pointsService->getPointsSummary($employee) : [];

        return view('orders.history', compact('orders', 'stats', 'employee'));
    }

    public function show(Request $request, Order $order)
    {
        $employee = $request->get('employee');
        
        if ($employee && $order->empleado_id !== $employee->id_empleado) {
            abort(403, 'No autorizado para ver este pedido');
        }

        $order->load('product');

        return view('orders.show', compact('order', 'employee'));
    }

    public function apiHistory(Request $request)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $query = Order::where('empleado_id', $employee->id_empleado)
            ->with('product');


        if ($request->has('status') && $request->status) {
            $query->where('estado', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            try {
                $query->whereDate('fecha', '>=', $request->date_from);
            } catch (\Exception $e) {

            }
        }

        if ($request->has('date_to') && $request->date_to) {
            try {
                $query->whereDate('fecha', '<=', $request->date_to);
            } catch (\Exception $e) {

            }
        }

        $orders = $query->orderBy('fecha', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'orders' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
                'prev_page_url' => $orders->previousPageUrl(),
                'next_page_url' => $orders->nextPageUrl(),
            ]
        ]);
    }

    public function apiShow(Request $request, Order $order)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }


        if ($order->empleado_id !== $employee->id_empleado) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para ver este pedido'
            ], 403);
        }

        $order->load('product');

        return response()->json([
            'success' => true,
            'id' => $order->id,
            'estado' => $order->estado,
            'puntos_utilizados' => $order->puntos_utilizados,
            'product_name' => $order->product ? $order->product->nombre : $order->producto_nombre,
            'product_image' => $order->product ? $order->product->imagen_url : null,
            'observaciones' => $order->observaciones,
            'notas_admin' => $order->notas_admin,
            'created_at' => $order->created_at->toISOString(),
        ]);
    }

    public function cancel(Request $request, Order $order)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }


        if ($order->empleado_id !== $employee->id_empleado) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado para cancelar este pedido'
            ], 403);
        }

        try {
            $result = $this->pointsService->cancelOrder($order, 'Cancelado por el usuario');

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}