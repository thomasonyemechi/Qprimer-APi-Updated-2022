<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Checker
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
        //reading this code ??? make the middleware stronger some day.....
        $token = $_GET['user_id'] ?? 0 ;
        if($token == 0) {
            return response([
                'message' => 'Access denied'
            ], 403);
        }
        $user = User::find($token);
        if(!$user) {
            return response([
                'message' => 'Invalid user token'
            ]);
        }
        return $next($request);
    }
}
