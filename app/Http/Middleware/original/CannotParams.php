<?php

namespace App\Http\Middleware\original;

use App\Http\Controllers\Common\Response;
use Closure;
use Illuminate\Http\Request;

class CannotParams
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
        if(!count($request->query())){
            return $next($request);
        }else{
            return Response::error(406005);
        }
    }
}
