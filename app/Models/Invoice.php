<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDate;

class Invoice extends Model
{
    use HasUuids, SoftDeletes, SerializeDate;


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
        'price_item',
        'price_sum',
        'customer',
        'discount',
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

    public const TYPE = [
        self::TAKE_AWAY,
        self::DINE_IN,
    ];
    public const TAKE_AWAY = 'take away';
    public const DINE_IN = 'dine in';

    public const STATUS = [
        self::PENDING,
        self::SUCCESS,
        self::CANCEL,
    ];
    public const PENDING = 'pending';
    public const SUCCESS = 'success';
    public const CANCEL = 'cancel';

    public const PAYMENT = [
        self::DEBIT,
        self::CASH,
        self::QRIS,
    ];
    public const DEBIT = 'debit';
    public const CASH = 'cash';
    public const QRIS = 'qris';


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
