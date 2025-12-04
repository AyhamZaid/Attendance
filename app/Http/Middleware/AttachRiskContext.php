<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class AttachRiskContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Add IP hash
        $ip = $request->ip();
        $request->merge(['ip_hash' => Hash::make($ip)]);

        // Add geo confidence if provided
        if ($request->has('geo_confidence')) {
            $geoConfidence = $request->input('geo_confidence');
            // Manual float coercion for PHP 7.4 compatibility
            $request->merge(['geo_confidence' => (float) $geoConfidence]);
        }

        return $next($request);
    }
}


