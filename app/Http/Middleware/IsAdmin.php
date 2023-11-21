<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
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
        if (auth()->user() && !auth()->user()->company_roles->first()->company->sub_domain) {
            if (auth()->user()->company_roles->contains('role',2) || auth()->user()->company_roles->contains('role',5)) {
                return $next($request);
            }
        }
        return redirect('/')->with('error', "You don't have admin access.");
    }
}
