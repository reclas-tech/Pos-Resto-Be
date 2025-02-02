<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait SerializeDate
{
    /**
     * @param \DateTimeInterface $date
     * 
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::parse($date, config('app.timezone'))->toIso8601String();
    }
}
