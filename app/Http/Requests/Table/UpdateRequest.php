<?php

namespace App\Http\Requests\Table;

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
            'name' => 'bail|required|string|unique:tables|max:255',
            'capacity' => 'bail|required|numeric|min:0',
            'location' => 'bail|required|string|in:indoor,outdoor',
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
            'capacity' => 'Kapasitas',
            'location' => 'Lokasi',
        ];
    }

    public function messages(): array
    {
        return [
            'unique' => ':attribute sudah digunakan.',
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute harus lebih besar dari 0.',
            'in' => ':attribute harus diisi dengan indoor atau outdoor.',
        ];
    }
}
