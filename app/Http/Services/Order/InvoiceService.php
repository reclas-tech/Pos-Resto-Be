<?php

namespace App\Http\Services\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\PrinterSetting;

class InvoiceService extends Service
{
	public static function KitchenPrint(array $kitchens, array $tables): string
	{
		$print = PrinterSetting::first();

		$printPath = config('app.print_kitchen_api');

		$printURL = $print?->link ?? config('app.print_url');
		$printCut = $print?->cut ?? config('app.print_cut');

		if (substr($printURL, -1) === '/') {
			$printURL = substr($printURL, 0, strlen($printURL) - 1) . $printPath;
		}

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