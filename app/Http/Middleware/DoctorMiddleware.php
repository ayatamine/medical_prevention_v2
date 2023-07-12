<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!request()->user()->tokenCan('role:doctor'))
        {
            // return abort(401);
            return response()->json([
                'success'=>false,
                'message'=>'You are not allowed to do this action , only for doctors'
            ]);
        }
        return $next($request);
    }
}
