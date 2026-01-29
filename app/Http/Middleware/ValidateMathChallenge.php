<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMathChallenge
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post') && $request->routeIs('login')) {
            $answer = $request->input('math_answer');
            $expected = session('math_challenge_answer');

            if ($answer === null || (int)$answer !== (int)$expected) {
                return back()->withErrors(['math_answer' => 'Security challenge failed. Please try again.'])->withInput();
            }
            
            // Clear the answer after successful validation
            session()->forget('math_challenge_answer');
        }

        return $next($request);
    }
}
