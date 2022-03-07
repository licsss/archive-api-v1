<?php

namespace App\Http\Middleware\original;

use App\Http\Controllers\Common\Response;
use Closure;
use Illuminate\Http\Request;

class icon
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
        if($request->isMethod('POST') && $request->url()=='http://localhost/licsss/api/v1/public/icon'){//https://file.licsss.com/icon)
            return $next($request);
        }else{
            if(strpos($request->url(),'https://download.licsss.com/')!==false){
                return $next($request);
            }
        }
        return response()->json(Response::error(404000),404);
    }
}
