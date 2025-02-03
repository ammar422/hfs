<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->guard('api')->user();

        if (!empty($user) && $user->account_status == 'pending') {
            return lynx()->status(301)->message(__('users::auth.pending'))->response();
        } elseif (!empty($user) && $user->account_status == 'ban') {
            return lynx()->status(302)->message(__('users::auth.banned'))->response();
        }

        return $next($request);
    }
}
