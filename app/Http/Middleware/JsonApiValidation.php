<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonApiValidation
{
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
            

            if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
                if (!$request->expectsJson() && !$request->isJson()) {
                    return response()->json([
                        'error' => 'Content-Type must be application/json',
                        'message' => 'API endpoints require JSON content type'
                    ], 400);
                }
            }
            

            $this->sanitizeInputs($request);
        }

        return $next($request);
    }

    private function sanitizeInputs(Request $request): void
    {
        $input = $request->all();
        
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {

                $value = str_replace("\0", '', trim($value));
                

                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
            }
        });
        
        $request->merge($input);
    }
}
