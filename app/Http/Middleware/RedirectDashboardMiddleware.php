<?php

namespace App\Http\Middleware;

use App\Models\RoasterStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RedirectDashboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // return auth()->user();
        if(auth()->user()){
            if(auth()->user()->company_roles->first()->company->sub_domain){
                // return redirect('http://localhost:8888/autologin?id='.auth()->user()->id.'&api_token=token');
                return redirect('http://'.auth()->user()->company_roles->first()->company->sub_domain .'.easytask.com.au/autologin?id='.auth()->user()->id.'&api_token=token');
            }else{
                
                if(auth()->user()->company_roles->first()->role != 1){
                    $roaster_statuses = RoasterStatus::where([
                        ['company_code', Auth::user()->company_roles->first()->company->id],
                    ])
                    ->orderBy('id', 'ASC')
                    ->groupBy('name')
                    ->get();

                    $roaster_status = [];
                    foreach ($roaster_statuses as $status) {
                        $roaster_status[$status->name] = $status->id;
                    }
                    Session::put('roaster_status', $roaster_status);
                }

                if (auth()->user()->company_roles->first()->role== 2 || auth()->user()->company_roles->first()->role== 5 || auth()->user()->company_roles->first()->role== 6 || auth()->user()->company_roles->first()->role==7) {
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

        return $next($request);
    }
}
