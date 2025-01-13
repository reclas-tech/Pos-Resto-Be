<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;
use App\Models\Invoice;

class CreateRequest extends FormRequest
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
            'products' => 'bail|nullable|required_without:packets|array',
            'packets' => 'bail|nullable|required_without:products|array',

            'products.*' => 'bail|array',
            'packets.*' => 'bail|array',

            'products.*.quantity' => 'bail|required_with:products|numeric|integer|min:1|max_digits:8',
            'products.*.id' => 'bail|required_with:products|string|distinct|exists:products,id',
            'products.*.note' => 'bail|nullable|string|max:255',

            'packets.*.quantity' => 'bail|required_with:packets|numeric|integer|min:1|max_digits:8',
            'packets.*.id' => 'bail|required_with:packets|string|distinct|exists:packets,id',
            'packets.*.note' => 'bail|nullable|string|max:255',

            'tables' => 'bail|nullable|required_if:type,' . Invoice::DINE_IN . '|array',
            'tables.*' => 'bail|string|distinct|exists:tables,id',

            'type' => 'bail|required|string|in:' . Invoice::TAKE_AWAY . ',' . Invoice::DINE_IN,
            'customer' => 'bail|required|string|max:255',
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
            'products.*.note' => 'Catatan produk',

            'packets' => 'Daftar paket',
            'packets.*' => 'Paket',
            'packets.*.quantity' => 'Banyak paket',
            'packets.*.id' => 'ID paket',
            'packets.*.note' => 'Catatan paket',

            'tables' => 'Daftar Meja',
            'tables.*' => 'Meja',

            'customer' => 'Nama pelanggan',
            'type' => 'Tipe pesanan',
        ];
    }
}
