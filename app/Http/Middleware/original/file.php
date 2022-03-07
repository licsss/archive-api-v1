<?php

namespace App\Http\Middleware\original;

use App\Http\Controllers\Common\Response;
use Closure;
use Illuminate\Http\Request;

class file
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
        if($request->isMethod('POST') && $request->url()=='http://localhost/licsss/api/v1/public' && !empty($request->header('Licsss-Srv'))){//https://file.licsss.com/)
            return $next($request);
        }elseif($request->isMethod('GET') && strpos($request->headers->get('referer'),'localhost')!==false){//licsss.com
            if(strpos($request->url(),'https://download.licsss.com/')!==false || strpos($request->url(),'https://file.licsss.com/')!==false || strpos($request->url(),'localhost/licsss/api/v1/public')!==false){
                return $next($request);
            }
        }
        return Response::error(404000);
    }
}
