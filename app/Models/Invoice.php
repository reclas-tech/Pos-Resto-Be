<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuids, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'invoices';

    protected $fillable = [
        // UNIQUE
        'code',

        // REQUIRED
        'price_sum',
        'customer',
        'profit',
        'status',
        'type',
        'tax',

        // OPTIONAL
        'payment',

        // FOREIGN KEY
        'created_by',
        'updated_by', // NULLABLE
        'cashier_id' // NULLABLE
    ];

    protected $hidden = [];

    public static const TYPE = [
        Invoice::TAKE_AWAY,
        Invoice::DINE_IN,
    ];
    public static const TAKE_AWAY = 'take away';
    public static const DINE_IN = 'dine in';

    public static const STATUS = [
        Invoice::PENDING,
        Invoice::SUCCESS,
        Invoice::CANCEL,
    ];
    public static const PENDING = 'debit';
    public static const SUCCESS = 'qris';
    public static const CANCEL = 'cash';

    public static const PAYMENT = [
        Invoice::DEBIT,
        Invoice::CASH,
        Invoice::QRIS,
    ];
    public static const DEBIT = 'debit';
    public static const CASH = 'cash';
    public static const QRIS = 'qris';


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(InvoiceTable::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public function packets(): HasMany
    {
        return $this->hasMany(InvoicePacket::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */
}
