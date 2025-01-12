<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CashierShift extends Model
{
    use HasUuids, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'cashier_shifts';

    protected $fillable = [
        // REQUIRED
        'cash_on_hand_start',
        'started_at',

        // OPTIONAL
        'cash_on_hand_end',
        'ended_at',

        // FOREIGN KEY
        'cashier_id'
    ];

    protected $hidden = [];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */
}
