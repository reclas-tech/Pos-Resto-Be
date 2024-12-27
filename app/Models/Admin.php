<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;

class Admin extends User implements JWTSubject
{
    use HasUuids, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'admins';

    protected $fillable = [
        // UNIQUE
        'email',

        // REQUIRED
        'password',
        'name',

        // OPTIONAL
        'otp',
    ];

    protected $hidden = [
        'password',
        'otp',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function refreshToken(): HasMany
    {
        return $this->hasMany(AdminRefreshToken::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function setRefreshToken(string $token, Carbon|string $exp): AdminRefreshToken
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
