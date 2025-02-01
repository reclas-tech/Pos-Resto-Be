<?php

namespace App\Http\Services\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Http\Services\Service;
use App\Models\PrinterSetting;
use App\Models\InvoiceProduct;
use App\Models\InvoicePacket;
use App\Models\InvoiceTable;
use App\Models\Invoice;

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
			$response = Http::asJson()->withHeaders([
				'Authorization' => config('app.print_token'),
				'ngrok-skip-browser-warning' => 'true',
			])->post($printURL, ['data' => $data]);
			return $response->body();
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * @param string $id
	 * 
	 * @return bool
	 */
	public static function CheckerPrint(string $id): bool
	{
		$invoice = Invoice::where('type', Invoice::DINE_IN)->whereKey($id)->first();

		if ($invoice) {
			$print = PrinterSetting::first();

			$printPath = config('app.print_checker_api');

			$printURL = $print?->link ?? config('app.print_url');

			if (substr($printURL, -1) === '/') {
				$printURL = substr($printURL, 0, strlen($printURL) - 1);
			}

			$printURL .= $printPath;

			$data = [
				...$invoice->only([
					'code',
					'customer',
					'created_at',
				]),
				'products' => $invoice->products()->withTrashed()->get()->map(function (InvoiceProduct $invoiceProduct): array {
					return [
						...$invoiceProduct->only([
							'quantity',
							'note',
						]),
						'name' => $invoiceProduct->product()->withTrashed()->first()?->name ?? '',
					];
				}),
				'packets' => $invoice->packets()->withTrashed()->get()->map(function (InvoicePacket $invoicePacket): array {
					return [
						...$invoicePacket->only([
							'quantity',
							'note',
						]),
						'name' => $invoicePacket->packet()->withTrashed()->first()?->name ?? '',
					];
				}),
				'tables' => $invoice->tables()->withTrashed()->get()->map(function (InvoiceTable $invoiceTable): string {
					return $invoiceTable->table()->withTrashed()->first()?->name ?? '';
				}),
				'ip' => $print?->checker_ip ?? '',
			];

			try {
				$response = Http::asJson()->withHeaders([
					'Authorization' => config('app.print_token'),
					'ngrok-skip-browser-warning' => 'true',
				])->post($printURL, $data);

				return $response->successful();
			} catch (\Exception $e) {
				return false;
			}
		}

		return false;
	}
}