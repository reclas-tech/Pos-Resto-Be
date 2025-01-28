<?php

namespace App\Http\Controllers\Printer;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PrinterGetController extends BaseController
{
    public function action(): JsonResponse
    {
        $printerSetting = $this->printerService->get();

        return Response::SetAndGet(message: 'Berhasil mendapatkan pengaturan printer', data: $printerSetting);
    }
}
