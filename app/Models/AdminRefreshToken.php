<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDate;

class AdminRefreshToken extends Model
{
    use HasUuids, SoftDeletes, SerializeDate;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'admin_refresh_tokens';

    protected $fillable = [
        // REQUIRED
        'expired_at',
        'token',

        // FOREIGN KEY
        'admin_id'
    ];

    protected $hidden = [
        'admin_id',
        'token',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Functions
    |--------------------------------------------------------------------------
    */
}
