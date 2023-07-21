<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(auth()->user()->company_roles->first()->company->sub_domain){
                    // return redirect('http://localhost:8888/autologin?id='.auth()->user()->id.'&api_token=token');
                    return redirect('http://'.auth()->user()->company_roles->first()->company->sub_domain .'.easytask.com.au/autologin?id='.auth()->user()->id.'&api_token=token');
                }else{
                    if (auth()->user()->company_roles->first()->role== 2) {
                        return redirect('/admin/home/{id}');
                    } elseif (auth()->user()->company_roles->first()->role== 1) {
                        return redirect()->route('super-admin.home');
                    } elseif (auth()->user()->company_roles->first()->role== 4) {
                        return redirect()->route('supervisor.dashboard');
                    }
                    else {
                        return redirect()->route('home');
                    }
                }
            }
        }

        return $next($request);
    }
}
