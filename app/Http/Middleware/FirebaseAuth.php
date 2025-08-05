<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Employee;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\InvalidArgumentException;

class FirebaseAuth
{
    protected ?Auth $auth;

    public function __construct()
    {

        $this->auth = null;
    }

    public function handle(Request $request, Closure $next)
    {

        if (!$this->auth) {
            try {
                $this->auth = app(Auth::class);
            } catch (\Exception $e) {
                \Log::error('Firebase Auth initialization failed', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Sistema de autenticación no configurado. Contacte al administrador.',
                    'error_code' => 'FIREBASE_NOT_CONFIGURED',
                    'debug' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }


        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'success' => false,
                'message' => 'No se proporcionó token de autenticación o formato inválido.'
            ], 401);
        }

        $idToken = substr($authHeader, 7); // Remove 'Bearer ' prefix

        try {

            $verifiedIdToken = $this->auth->verifyIdToken($idToken);
            $firebaseUser = $verifiedIdToken->claims()->all();


            $employee = Employee::where('id_empleado', $firebaseUser['sub'])->first();

            if (!$employee) {

                \Log::info('Auto-registering new Firebase user', [
                    'firebase_uid' => $firebaseUser['sub'],
                    'email' => $firebaseUser['email'] ?? 'no-email',
                    'name' => $firebaseUser['name'] ?? 'Usuario'
                ]);

                $employee = Employee::create([
                    'id_empleado' => $firebaseUser['sub'],
                    'nombre' => $firebaseUser['name'] ?? $firebaseUser['email'] ?? 'Usuario',
                    'email' => $firebaseUser['email'] ?? '',
                    'puntos_totales' => 0,
                    'puntos_canjeados' => 0,
                    'rol_usuario' => 'Empleado', // Default role
                ]);
            }


            $request->merge([
                'firebase_user' => $firebaseUser,
                'employee' => $employee,
                'user_role' => $employee->rol_usuario,
                'is_admin' => $employee->is_admin,
            ]);

            return $next($request);

        } catch (InvalidArgumentException $e) {
            \Log::warning('Invalid Firebase token attempt', [
                'error' => $e->getMessage(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Token de autenticación inválido.',
                'error_code' => 'INVALID_TOKEN',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 401);

        } catch (AuthException $e) {
            if (str_contains($e->getMessage(), 'expired')) {
                \Log::info('Expired Firebase token', [
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                    'error_code' => 'TOKEN_EXPIRED'
                ], 401);
            }

            \Log::warning('Firebase Auth exception', [
                'error' => $e->getMessage(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de autenticación. Por favor, inicia sesión nuevamente.',
                'error_code' => 'AUTH_ERROR',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 403);

        } catch (\Exception $e) {
            \Log::error('Firebase Auth unexpected error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del sistema. Por favor, contacte al soporte técnico.',
                'error_code' => 'INTERNAL_ERROR',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}