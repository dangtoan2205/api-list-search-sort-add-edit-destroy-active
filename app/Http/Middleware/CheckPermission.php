<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $name = $request->route()->getName();
        if ($name && !auth()->guard('api')->user()->can($name)) {
            return response()->json(['message' => 'You don\'t have permission to do this'], 403);
        }

        return $next($request);
    }
}
