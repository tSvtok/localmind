<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ajouter ceci
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) { // Vérifie si l'utilisateur n'est pas authentifié
            return redirect('/login'); // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié²

        }

       return $next($request);
    }
}
