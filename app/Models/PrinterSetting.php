<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    use HasUuids;


    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    protected $table = 'printer_settings';

    protected $fillable = [
        // REQUIRED
        'link',
        'cut',
    ];

    protected $hidden = [];
}