<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;

class Employee extends User implements JWTSubject
{
    use HasUuids, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';

    protected $fillable = [
        // UNIQUE
        'pin',

        // REQUIRED
        'address',
        'phone',
        'name',
        'role',
    ];

    protected $hidden = [];

    public static const ROLE = [
        Employee::CASHIER,
        Employee::WAITER,
    ];
    public static const CASHIER = 'cashier';
    public static const WAITER = 'waiter';


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function refreshToken(): HasMany
    {
        return $this->hasMany(EmployeeRefreshToken::class);
    }

    public function cashOnHands(): HasMany
    {
        return $this->hasMany(CashOnHand::class, 'cashier_id');
    }

    public function times(): HasMany
    {
        return $this->hasMany(CashierTime::class, 'cashier_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */

    public function setRefreshToken(string $token, Carbon|string $exp): EmployeeRefreshToken
    {
        return $this->refreshToken()->create([
            'expired_at' => $exp,
            'token' => $token,
        ]);
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }
}
