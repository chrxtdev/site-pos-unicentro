<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        // Permite Acesso se o usuário tiver legacy is_admin ou possuir alguma role definida
        if ($user && ($user->is_admin || $user->roles()->count() > 0)) {
            return $next($request);
        }

        return redirect()->route('aluno.portal')->with('error', 'Acesso negado. Apenas administradores podem acessar esta área.');
    }
}