<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;

class UpdateRequest extends FormRequest
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
            'products' => 'bail|nullable|array',
            'packets' => 'bail|nullable|array',

            'products.*' => 'bail|array',
            'packets.*' => 'bail|array',

            'products.*.quantity' => 'bail|required_with:products|numeric|integer|min:0|max_digits:8',
            'products.*.id' => 'bail|required_with:products|string|distinct|exists:invoice_products,id',

            'packets.*.quantity' => 'bail|required_with:packets|numeric|integer|min:0|max_digits:8',
            'packets.*.id' => 'bail|required_with:packets|string|distinct|exists:invoice_packets,id',

            'pin' => 'bail|nullable|string|min:6|max:6',
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
            'products' => 'Daftar produk',
            'products.*' => 'Produk',
            'products.*.quantity' => 'Banyak produk',
            'products.*.id' => 'ID produk',

            'packets' => 'Daftar paket',
            'packets.*' => 'Paket',
            'packets.*.quantity' => 'Banyak paket',
            'packets.*.id' => 'ID paket',

            'pin' => 'PIN',
        ];
    }
}
