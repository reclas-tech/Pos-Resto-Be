<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDate;

class EmployeeRefreshToken extends Model
{
    use HasUuids, SoftDeletes, SerializeDate;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'employee_refresh_tokens';

    protected $fillable = [
        // REQUIRED
        'expired_at',
        'token',

        // FOREIGN KEY
        'employee_id'
    ];

    protected $hidden = [
        'employee_id',
        'token',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */
}
