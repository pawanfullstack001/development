<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Checksubadmin
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
        if(!Auth::guard('subadmin')->check()){
            return redirect()->to('subadmin/login');
        }
        return $next($request);
    }
}
