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

    public const ROLE = [
        self::CASHIER,
        self::WAITER,
    ];
    public const CASHIER = 'cashier';
    public const WAITER = 'waiter';


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function refreshToken(): HasMany
    {
        return $this->hasMany(EmployeeRefreshToken::class);
    }

    public function shifts(): HasMany
    {
        return $this->hasMany(CashierShift::class, 'cashier_id');
    }

    public function createdInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function updatedInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'updated_by');
    }

    public function checkOutInvoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'cashier_id');
    }

    public function updatedInvoicePackets(): HasMany
    {
        return $this->hasMany(InvoicePacket::class, 'updated_by');
    }

    public function updatedInvoiceProducts(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class, 'updated_by');
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

    public function softOrForceDelete(): bool
    {
        try {
            if ($this->shifts->count() || $this->createdInvoices->count() || $this->updatedInvoices->count() || $this->checkOutInvoices->count() || $this->updatedInvoicePackets->count() || $this->updatedInvoiceProducts->count()) {
                $this->delete();
            } else {
                $this->refreshToken()->forceDelete();
                $this->forceDelete();
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
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
