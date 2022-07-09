<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permissions)
    {
        
        $permissions_array = explode('|', $permissions);
        $id = Auth::guard('admin')->user()->id;
       
        foreach($permissions_array as $permission){
            if(Auth::guard('admin')->user()->hasPermission($permission,$id)){
                return $next($request);
            }else{
                return response()->view('admin.401');
            }
        }

        return redirect()->back();

    }
}


