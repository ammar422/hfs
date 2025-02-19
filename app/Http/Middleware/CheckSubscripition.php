<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscripition
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        $subscripition = $user->subscription()->exists();
        if (!$subscripition)
            return $next($request);
        return lynx()
            ->message('You are already subscribed to the ' . $user->subscription->name . ' package and your subscripition ends at ' . $user->subscription->expired_at)
            ->response();
    }
}
