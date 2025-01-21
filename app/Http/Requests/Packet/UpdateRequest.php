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
            'price' => 'bail|required|numeric|integer',
            'stock' => 'bail|required|numeric|integer',
            'cogp' => 'bail|required|numeric|integer',
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

    public function messages(): array
    {
        return [
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute tidak ditemukan.',
            'integer' => ':attribute harus berupa integer.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file jpeg, png, jpg, svg.',
            'image.max' => ':attribute tidak boleh lebih dari :max kilobytes.',
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'string' => ':attribute harus berupa teks.',
            'array' => ':attribute harus berupa array.',
        ];
    }
}
