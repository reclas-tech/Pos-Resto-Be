<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Response as HelpersResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Employee;
use Closure;

class EmployeeAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string|null $role = null): Response
    {
        try {
            $payload = $request->attributes->get('jwt_payload', []);

            if (isset($payload['sub'])) {
                $employee = Employee::whereHas('refreshToken', function (Builder $query) use ($payload, $role): void {
                    $query->whereKey($payload['sub']);
                    if ($role !== null) {
                        $query->where('role', $role);
                    }
                })->first();

                if ($employee !== null) {
                    $request->attributes->add(['user_id' => $employee->id]);
                    $request->attributes->remove('jwt_payload');

                    auth('api-employee')->login($employee);

                    return $next($request);
                }
            }

            throw new \Exception();
        } catch (\Exception $e) {
            return HelpersResponse::SetAndGet(HelpersResponse::UNAUTHORIZED, 'Anda tidak memiliki akses');
        }
    }
}
