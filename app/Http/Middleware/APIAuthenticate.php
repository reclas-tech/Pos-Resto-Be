<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Response as HelpersResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Closure;

class APIAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken(JWTAuth::getToken())->getPayload();

            $request->attributes->add(['jwt_payload' => $payload]);

            return $next($request);
        } catch (\Exception $e) {
            return HelpersResponse::SetAndGet(HelpersResponse::UNAUTHORIZED, 'Anda tidak memiliki akses');
        }
    }
}
