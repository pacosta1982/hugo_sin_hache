<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonApiValidation
{
    public function handle(Request $request, Closure $next): Response
    {

