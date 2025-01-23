<?php

namespace App\Http\Services\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Http\Services\Service;
use App\Models\PrinterSetting;

class InvoiceService extends Service
{
	public static function Print(array $kitchens, array $tables)
	{
		$printURL = PrinterSetting::first()?->link ?? config('app.print_url');

		foreach ($kitchens as $invoice) {
			Pdf::view('pdf.kitchen', [...$invoice, 'tables' => $tables])->save($invoice['id'] . '.pdf');
			$invoice['file'] = chunk_split(base64_encode(file_get_contents($invoice['id'] . '.pdf')));
			Storage::delete($invoice['id'] . '.pdf');
		}

		return Http::asJson()->post($printURL, $kitchens);
	}
}
