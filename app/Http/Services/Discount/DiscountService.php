<?php

namespace App\Http\Services\Discount;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Services\Service;

class DiscountService extends Service
{

	/**
     * 
	 * 
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
    public function getAll(): Collection
	{
		$discount = Discount::latest()->select('percent')->get();

        return $discount;
	}
	
}
