<?php

namespace App\Http\Requests\Packet;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;
use Illuminate\Validation\Rule;

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
            'name' => ['bail', 'required', 'string', 'max:255', Rule::unique('packets', 'name')->ignore($this->route('id'))->withoutTrashed()],
            'price' => 'bail|required|numeric|integer|gt:cogp',
            'stock' => 'bail|required|numeric|integer',
            'cogp' => 'bail|required|numeric|integer|lt:price|min:0',
            'image' => 'bail|image|mimes:jpeg,png,jpg,svg|max:10240',
            'products' => 'bail|required|array',
            'products.*.id' => ['bail', 'required', 'string', Rule::exists('products', 'id')->withoutTrashed()],
            'products.*.quantity' => 'bail|required|numeric|integer',
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
            'name' => 'Nama',
            'price' => 'Harga',
            'stock' => 'Stok',
            'cogp' => 'HPP',
            'image' => 'Gambar',
            'products' => 'Produk',
            'products.*.id' => 'Produk',
            'products.*.quantity' => 'Jumlah Produk',
        ];
    }
}