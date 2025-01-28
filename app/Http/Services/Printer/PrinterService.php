<?php

namespace App\Http\Services\Printer;

use App\Http\Services\Service;
use App\Models\PrinterSetting;

class PrinterService extends Service
{
	public function get(): PrinterSetting|null
	{
		return PrinterSetting::first()?->only(['checker_ip', 'link']);
	}
}
