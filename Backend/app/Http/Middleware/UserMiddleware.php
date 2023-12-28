<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && !Auth::user()->is_active){

            $banned =Auth::user()->is_active==0;
            Auth::logout();
            $message = '';
            if($banned==1){
                $message='حسابك قد تم حظره. منفضلك راجع مدير الموقع' ;
            }

            return redirect()->route('login')
            ->with('status',$message)
            ->withErrors(['email'=>'لقد تم حظر حسابك من قبل المدير']);


        }
        return $next($request);
    }
}
