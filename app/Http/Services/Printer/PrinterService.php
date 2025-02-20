<?php

namespace App\Http\Services\Printer;

use App\Http\Services\Service;
use App\Models\PrinterSetting;

class PrinterService extends Service
{
	/**
	 * @return array|null
	 */
	public function get(): array|null
	{
		return PrinterSetting::first()?->only(['checker_ip', 'link']);
	}

	/**
	 * @param string $checkerIp
	 * @param string $link
	 * 
	 * @return bool
	 */
	public function update(string $checkerIp, string $link): bool
	{
		$setting = PrinterSetting::firstOrNew();

		$setting->checker_ip = $checkerIp;
		$setting->link = $link;

		$setting->cut ??= true;

		return $setting->save();
	}
}
