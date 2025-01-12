<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;
use App\Models\Employee;

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
            'role' => 'bail|required|string|in:' . Employee::CASHIER . ',' . Employee::WAITER,
            'pin' => 'bail|required|numeric|integer|digits:6',
            'address' => 'bail|required|string|max:65000',
            'phone' => 'bail|required|string|max:15',
            'name' => 'bail|required|string|max:255',
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
            'address' => 'Alamat',
            'phone' => 'Nomor HP',
            'role' => 'Peran',
            'name' => 'Nama',
            'pin' => 'PIN',
        ];
    }
}
