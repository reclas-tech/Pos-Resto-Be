<?php

namespace App\Http\Controllers\Discount;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class DiscountGetAllController extends BaseController
{
    public function action(): JsonResponse
    {
        $discount =  $this->discountService->getAll();
        return Response::SetAndGet(message: 'Semua Diskon Berhasil Didapatkan', data: $discount->toArray());
    }
}
