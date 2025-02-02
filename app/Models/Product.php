<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDate;

class Product extends Model
{
    use HasUuids, SoftDeletes, SerializeDate;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'products';

    protected $fillable = [
        'name',

        // REQUIRED
        'image',
        'price',
        'stock',
        'cogp',

        // FOREIGN KEY
        'category_id',
        'kitchen_id'
    ];

    protected $hidden = [];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(Kitchen::class);
    }

    public function invoiceProduct(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function packetProduct(): HasMany
    {
        return $this->hasMany(PacketProduct::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */

}
