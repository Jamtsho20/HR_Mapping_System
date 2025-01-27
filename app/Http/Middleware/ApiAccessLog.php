<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiAccessLog as AccessLog;

class ApiAccessLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd($request->all());
        $response = $next($request);

        AccessLog::create([
            'consumer' => 'TIPL HRMS',
            'bearer_token' => $request->bearerToken(),
            'mas_employee_id' => $request->user() ? $request->user()->id : NULL,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip()
        ]);

        return $response;
    }
}
