<?php

namespace App\Http\Services\Tax;

use App\Http\Services\Service;

class TaxService extends Service
{
	/**
	 * @return mixed
	 */
	public function get(): mixed
	{
		return config('app.tax');
	}
}
