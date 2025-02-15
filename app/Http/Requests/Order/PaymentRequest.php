<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;
use App\Models\Invoice;

class PaymentRequest extends FormRequest
{
    use RequestErrorMessage;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'method' => 'bail|required|string|in:' . Invoice::CASH . ',' . Invoice::DEBIT . ',' . Invoice::QRIS,

            'discount_id' => 'bail|nullable|string|exists:discounts,id',
        ];
    }

    /**
     * Aliases name
     * 
     * @return array
     */
    public function attributes(): array
    {
        return [
            'method' => 'Metode pembayaran',

            'discount_id' => 'Potongan harga',
        ];
    }
}
