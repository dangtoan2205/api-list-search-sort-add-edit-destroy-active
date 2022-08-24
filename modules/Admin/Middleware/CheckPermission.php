<?php

namespace Modules\Admin\Middleware;

use Auth;
use Closure;
use Config;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  mixed   $request Request.
     * @param  Closure $next    Closure.
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $name = $request->route()->getName();
        if ($name && config('constant.authorization') && !Auth::user()->can($name)) {
            return response()->json(['message' => 'You don\'t have permission to do this'], 403);
        }

        return $next($request);
    }
}
