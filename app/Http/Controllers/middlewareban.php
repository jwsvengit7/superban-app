<?php

namespace App\Http\Controllers;

use Closure;

class SuperbanMiddleware
{
    public function handle($request, Closure $next, $parameters)
    {
       
        list($limit, $interval, $banDuration) = explode(',', $parameters);

        app('superban')->trackRequest(
            $request->user()->id, 
            $request->ip(),
            $request->user()->email,
        );

        app('superban')->applyBan(
            $request->user()->id,
            $request->ip(),
            $request->user()->email,
            $limit,
            $interval,
            $banDuration
        );

    
        return $next($request);
    }
}
