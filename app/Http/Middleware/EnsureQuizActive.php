<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureQuizActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get data for current quiz when request
        $quiz = $request->route('quiz');
        $now  = now();

        // Check quiz if stall available
        $isNotPublished = !$quiz->is_published;
        $hasNotStarted  = $quiz->start_at && $now->lt($quiz->start_at);
        $ended          = $quiz->end_at && $now->gt($quiz->end_at);

        if ($isNotPublished || $hasNotStarted || $ended) {
            abort(401,'This quiz is not active');
        }

        return $next($request);
    }
}
