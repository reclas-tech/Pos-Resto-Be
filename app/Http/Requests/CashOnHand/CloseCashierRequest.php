<?php

namespace App\Http\Requests\CashOnHand;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;

class CloseCashierRequest extends FormRequest
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
            'cash' => ['bail', 'required', 'numeric', 'integer', 'min:0'],
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
            'cash' => 'Cash On Hand',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa integer.',
            'min' => ':attribute minimal :min.',
        ];
    }
}
