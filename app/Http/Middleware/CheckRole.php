<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    { 
        if (!auth()->check()) {
           Return redirect('/login');
        }

        $user = auth()->user();
        if (!$user->role || !in_array($user->role->nom,  $roles)) {
            
     abort(403, 'Accès non autorisé.');
        }
      return $next($request);
    }
}
