<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (@!($_SERVER['PHP_AUTH_USER'] == 'baby' && $_SERVER['PHP_AUTH_PW'] == 'harry')) {
            header('WWW-Authenticate: Basic realm="Babies"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Gotta login';
            exit;
        }

        return $next($request);
    }
}
