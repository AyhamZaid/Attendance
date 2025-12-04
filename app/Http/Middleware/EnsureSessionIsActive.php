<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\TrainingSession;

class EnsureSessionIsActive
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
        $session = $request->route('session');
        
        if ($session instanceof TrainingSession) {
            if (!$session->isActive()) {
                abort(403, 'Session is not currently active.');
            }
        }

        return $next($request);
    }
}


