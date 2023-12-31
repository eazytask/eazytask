<?php
namespace App\Http\Middleware;

use Closure;

class HttpsProtocolMiddleware {

    public function handle($request, Closure $next)
    {
            if (!$request->secure()) {
               return redirect()->secure($request->getRequestUri());
            }

            return $next($request); 
    }
}
?>