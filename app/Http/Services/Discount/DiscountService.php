<?php

namespace App\Http\Services\Discount;

use App\Http\Services\Service;
use App\Models\Discount;

class DiscountService extends Service
{
	/**
	 * @return array
	 */
	public function list(): array
	{
		return Discount::orderBy('value')->get()->toArray();
	}
}
