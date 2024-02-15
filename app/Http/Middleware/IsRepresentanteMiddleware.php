<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsRepresentanteMiddleware
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
        $representante_id = $request->representante->id ?? $request->representante_id;
       
        if (!$representante_id){
            $representante_id = $request->id;
        }

        if (!auth()->user()->is_admin && $representante_id != auth()->user()->is_representante) {
            abort(403);
        }
        
        return $next($request);
    }
}
