<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Http\Requests\UpdateProfileRequest;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Authentication handled by Firebase on frontend'
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }

    public function profile(Request $request)
    {
        $employee = $request->get('employee');

        return view('auth.profile', compact('employee'));
    }

    public function currentUser(Request $request)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'employee' => $employee,
            'firebase_user' => $request->get('firebase_user')
        ]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        try {
            $employee->update([
                'nombre' => $request->validated('nombre'),
                'email' => $request->validated('email'),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perfil actualizado exitosamente',
                    'employee' => $employee->fresh()
                ]);
            }

            return back()->with('success', 'Perfil actualizado exitosamente');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil'
                ], 400);
            }

            return back()->with('error', 'Error al actualizar el perfil');
        }
    }

    public function notifications(Request $request)
    {
        $employee = $request->get('employee');
        
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        try {

            $recentOrders = \App\Models\Order::byEmployee($employee->id_empleado)
                ->with('product')
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            $notifications = $recentOrders->map(function ($order) {
                $icon = match($order->estado) {
                    'pending' => '⏳',
                    'processing' => '🔄',
                    'completed' => '✅',
                    'cancelled' => '❌',
                    default => '📦'
                };

                $message = match($order->estado) {
                    'pending' => 'Tu pedido está pendiente',
                    'processing' => 'Tu pedido está siendo procesado',
                    'completed' => 'Tu pedido ha sido completado',
                    'cancelled' => 'Tu pedido fue cancelado',
                    default => 'Estado del pedido actualizado'
                };

                return [
                    'id' => $order->id,
                    'icon' => $icon,
                    'title' => $order->producto_nombre,
                    'message' => $message,
                    'time' => $order->updated_at->diffForHumans(),
                    'status' => $order->estado,
                    'is_read' => true,
                    'url' => route('orders.history')
                ];
            });

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar notificaciones'
            ], 500);
        }
    }
}