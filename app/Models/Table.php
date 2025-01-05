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

    public static const LOCATION = [
        Table::OUTDOOR,
        Table::INDOOR,
    ];
    public static const OUTDOOR = 'outdoor';
    public static const INDOOR = 'indoor';


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
