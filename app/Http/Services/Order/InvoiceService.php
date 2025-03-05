<?php

namespace App\Http\Services\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\PrinterSetting;

class InvoiceService extends Service
{
	/**
	 * @param array $kitchens
	 * @param array $tables
	 * 
	 * @return string
	 */
	public static function KitchenPrint(array $kitchens, array $tables): string
	{
		$print = PrinterSetting::first();

		$printPath = config('app.print_kitchen_api');

		$printURL = $print?->link ?? config('app.print_url');
		$printCut = $print?->cut ?? config('app.print_cut');

		if (substr($printURL, -1) === '/') {
			$printURL = substr($printURL, 0, strlen($printURL) - 1);
		}

		$printURL .= $printPath;

		$data = [];
		foreach ($kitchens as $invoice) {
			$data[] = [
				'ip' => $invoice->ip,
				'cut' => $printCut,
				'name' => $invoice->name,
				'date' => Carbon::parse($invoice->created_at)->format('Y-m-d h:i'),
				'code' => $invoice->invoice,
				'customer' => $invoice->customer,
				'products' => $invoice->products,
				'tables' => $tables,
			];
		}

		try {
			$response = Http::asJson()
				->withHeaders([
					'Authorization' => config('app.print_token'),
					'ngrok-skip-browser-warning' => 'true',
				])
				->post($printURL, ['data' => $data]);

			return $response->body();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * @param array $data
	 * 
	 * @return bool
	 */
	public static function CheckerPrint(array $data): bool
	{
		$print = PrinterSetting::first();

		$printPath = config('app.print_checker_api');

		$printURL = $print?->link ?? config('app.print_url');

		if (substr($printURL, -1) === '/') {
			$printURL = substr($printURL, 0, strlen($printURL) - 1);
		}

		$printURL .= $printPath;

		$data['ip'] = $print?->checker_ip ?? '';

		try {
			$response = Http::asJson()
				->withHeaders([
					'Authorization' => config('app.print_token'),
					'ngrok-skip-browser-warning' => 'true',
				])
				->post($printURL, $data);

			return $response->successful();
		} catch (\Exception $e) {
			return false;
		}
	}
}