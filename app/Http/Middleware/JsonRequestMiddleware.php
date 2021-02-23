<?php

namespace App\Http\Middleware;

use Closure;

class JsonRequestMiddleware
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
        // Pre-Middleware Action

       /* if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])
            && $request->isJson()
        ) {
            $data = $request->json()->all();
            $request->request->replace(is_array($data) ? $data : []);
        }

        $response = $next($request);

        // Post-Middleware Action

        return $response;

        */
    }
}
