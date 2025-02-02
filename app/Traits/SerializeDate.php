<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait SerializeDate
{
    /**
     * @param \DateTimeInterface $date
     * 
     * @return Carbon
     */
    protected function serializeDate(\DateTimeInterface $date): Carbon
    {
        return Carbon::parse($date, config('app.timezone'));
    }
}
