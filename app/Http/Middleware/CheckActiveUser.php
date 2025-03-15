<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;

class CheckActiveUser extends \Illuminate\Auth\Middleware\Authenticate
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('filament.auth.login'); // Modification ici
        }
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards); // Authentication check
       
        if (Auth::check() && !Auth::user()->active) {
            Auth::logout();
           
            throw new AuthenticationException('Votre compte est inactif.', $guards, route('filament.auth.login'));
        }
        
        return $next($request);
    }
}