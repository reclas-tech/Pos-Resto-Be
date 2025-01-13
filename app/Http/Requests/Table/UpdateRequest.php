<?php

namespace App\Http\Requests\Table;

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
            'name' => ['bail', 'required', 'string', 'max:255', Rule::unique('tables', 'name')->ignore($this->route('id'))->withoutTrashed()],
            'capacity' => 'bail|required|numeric|min:0|integer',
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
            'integer' => ':attribute harus berupa integer.',
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute tidak boleh lebih dari :max karakter.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute harus lebih besar dari 0.',
            'in' => ':attribute harus diisi dengan indoor atau outdoor.',
        ];
    }
}
