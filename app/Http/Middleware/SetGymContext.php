<?php
namespace App\Http\Middleware;
use Closure;
class SetGymContext {
    public function handle($req, Closure $next) {
        if (auth()->check()) app()->instance('currentGymId', auth()->user()->gym_id);
        return $next($req);
    }
}
