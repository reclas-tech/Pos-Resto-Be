<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CashOnHand extends Model
{
    use HasUuids, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'cash_on_hands';

    protected $fillable = [
        // REQUIRED
        'cash',

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
