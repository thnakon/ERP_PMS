<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPageVisit;
use Illuminate\Support\Facades\Route;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $routeName = Route::currentRouteName();
            if ($routeName) {
                UserPageVisit::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'route_name' => $routeName,
                    ],
                    [
                        'last_visited_at' => now(),
                    ]
                );
            }
        }

        return $next($request);
    }
}
