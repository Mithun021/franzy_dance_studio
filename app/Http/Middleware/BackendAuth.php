<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BackendAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!in_array(Auth::user()->user_type, ['admin', 'staff', 'faculty'])) {
            abort(403, 'Unauthorized Access.');
            // OR
            // return redirect()->route('home');
        }

        // if (Auth::user()->user_type == 'student') {
        //     return redirect()->route('student.dashboard');
        // }

        // if (!in_array(Auth::user()->user_type, ['student', 'user'])) {
        //     abort(403, 'Unauthorized Access.');
        //     // OR
        //     // return redirect()->route('admindashboard.get');
        // }

        return $next($request);
    }
}
