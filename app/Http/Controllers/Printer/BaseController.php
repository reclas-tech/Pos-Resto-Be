<?php

namespace App\Http\Controllers\Printer;

use App\Http\Services\Printer\PrinterService;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct(
        public PrinterService $printerService
    ) {
    }
}
