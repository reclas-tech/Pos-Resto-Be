<?php

namespace App\Http\Services\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\PrinterSetting;

class InvoiceService extends Service
{
	public static function Print(array $kitchens, array $tables)
	{
		$print = PrinterSetting::first();
		
		$printURL = $print?->link ?? config('app.print_url');
		$printCut = $print?->cut ?? config('app.print_cut');

		$data = [];
		foreach ($kitchens as $invoice) {
			$data[] = [
				'ip' => $invoice['ip'],
				'cut' => $printCut,
				'name' => $invoice['name'],
				'date' => Carbon::parse($invoice['created_at'])->format('Y-m-d h:i'),
				'code' => $invoice['invoice'],
				'customer' => $invoice['customer'],
				'products' => $invoice['products'],
				'tables' => $tables,
			];
		}
		
		try {
			$response = Http::asJson()->post($printURL, ['data' => $data]);
			return $response->body();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}
}