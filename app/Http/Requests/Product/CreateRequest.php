<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;

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
            'name' => 'bail|required|string|unique:products|max:255',
            'price' => 'bail|required|numeric',
            'stock' => 'bail|required|numeric',
            'category_id' => 'bail|required|string',
            'kitchen_id' => 'bail|required|string',
            'cogp' => 'bail|required|numeric',
            'image' => 'bail|required|image|mimes:jpeg,png,jpg,svg|max:10240',
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
            'unique' => ':attribute sudah digunakan.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file jpeg, png, jpg, svg.',
            'image.max' => ':attribute tidak boleh lebih dari :max kilobytes.',
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'string' => ':attribute harus berupa teks.',
        ];
    }
}
