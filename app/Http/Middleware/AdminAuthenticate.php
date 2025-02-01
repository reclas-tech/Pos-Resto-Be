<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Response as HelpersResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Admin;
use Closure;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $payload = $request->attributes->get('jwt_payload', []);

            if (isset($payload['sub'])) {
                $admin = Admin::whereHas('refreshToken', function (Builder $query) use ($payload): void {
                    $query->whereKey($payload['sub']);
                })->first();

                if ($admin !== null) {
                    $request->attributes->add(['user_id' => $admin->id]);

                    auth('api-admin')->login($admin);

                    return $next($request);
                }
            }

            throw new \Exception();
        } catch (\Exception $e) {
            return HelpersResponse::SetAndGet(HelpersResponse::UNAUTHORIZED, 'Anda tidak memiliki akses');
        }

    }
}
