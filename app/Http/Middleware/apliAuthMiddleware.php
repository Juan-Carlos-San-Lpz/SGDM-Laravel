<?php

namespace App\Http\Middleware;

use Closure;

class apliAuthMiddleware
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
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {
            return $next($request);
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'menssage' => 'El usuario no esta identificado'
            );
            return response()->json($data, $data['code']);
        }
    }
}