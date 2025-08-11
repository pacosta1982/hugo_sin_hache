<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\CacheService;
use Illuminate\Support\Facades\Log;

class CacheApiResponse
{
    private $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function handle(Request $request, Closure $next, string $ttl = null): Response
    {
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

