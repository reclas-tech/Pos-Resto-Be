<?php

namespace App\Http\Controllers\Printer;

use App\Http\Requests\Printer\UpdateRequest;
use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class PrinterUpdateController extends BaseController
{
    public function action(UpdateRequest $request): JsonResponse
    {
        [
            'checker_ip' => $checkerIp,
            'link' => $link,
        ] = $request;

        if (substr($link, -1) === '/') {
            $link = substr($link, 0, strlen($link) - 1);
        }

        $this->printerService->update($checkerIp, $link);

        return Response::SetAndGet(message: 'Berhasil memperbarui pengaturan printer');
    }
}
