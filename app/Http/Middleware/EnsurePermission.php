<?php
namespace App\Http\Middleware;
use Closure;
class EnsurePermission {
    public function handle($req, Closure $next, string $perm) {
        if (!auth()->check()) return redirect()->route('login');
        if (!auth()->user()->canAccess($perm)) abort(403, 'Sem permissão para: '.$perm);
        return $next($req);
    }
}
