<?php

namespace App\Http\Requests\Product;

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
            'name' => ['bail', 'required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($this->route('id'))->withoutTrashed()],
            'price' => 'bail|required|numeric|integer|gt:cogp',
            'stock' => 'bail|required|numeric|integer',
            'category_id' => ['bail', 'required', 'string', Rule::exists('categories', 'id')->withoutTrashed()],
            'kitchen_id' => ['bail', 'required', 'string', Rule::exists('kitchens', 'id')->withoutTrashed()],
            'cogp' => 'bail|required|numeric|integer|lt:price|min:0',
            'image' => 'bail|image|mimes:jpeg,png,jpg,svg|max:10240',
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
            'category_id' => 'Kategori',
            'kitchen_id' => 'Dapur',
            'cogp' => 'HPP',
            'image' => 'Gambar',
        ];
    }

    public function messages(): array
    {
        return [
            'image' => ':attribute harus berupa gambar.',
            'exists' => ':attribute tidak ditemukan.',
            'integer' => ':attribute harus berupa integer.',
            'mimes' => ':attribute harus berupa file jpeg, png, jpg, svg.',
            'image.max' => ':attribute tidak boleh lebih dari :max kilobytes.',
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'string' => ':attribute harus berupa teks.',
        ];
    }
}