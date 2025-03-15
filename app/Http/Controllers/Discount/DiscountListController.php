<?php

namespace App\Http\Controllers\Discount;

use Illuminate\Http\JsonResponse;
use App\Helpers\Response;

class DiscountListController extends BaseController
{
    public function action(): JsonResponse
    {
        $discounts = $this->discountService->list();

        return Response::SetAndGet(message: 'Berhasil mendapatkan daftar potongan harga', data: $discounts);
    }
}
