<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasUuids, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'tables';

    protected $fillable = [
        // UNIQUE
        'name',

        // REQUIRED
        'capacity',
        'location',
    ];

    protected $hidden = [];

    public const LOCATION = [
        self::OUTDOOR,
        self::INDOOR,
    ];
    public const OUTDOOR = 'outdoor';
    public const INDOOR = 'indoor';


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceTable::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */
}
