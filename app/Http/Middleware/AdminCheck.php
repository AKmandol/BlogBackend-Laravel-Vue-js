<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminCheck
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
        //Log::info('inside middle');  
        if($request->path() == 'app/admin_login'){
            return $next($request);
        }

        if(!Auth::check()){
            
            return response()->json([
                'msg' => 'Email Password does not match...',
                'url' => $request->path()
            ],403);
        }

        $user = Auth::user();
        if($user->role->isAdmin == 0 ){
            return response()->json([
                'msg' => 'You are not allowed...'
            ],403);
        }


        return $next($request);
    }
}
