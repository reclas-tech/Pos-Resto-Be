<?php

namespace App\Http\Requests\Table;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestErrorMessage;
use Illuminate\Validation\Rule;

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
            'name' => ['bail', 'required', 'string', 'max:255', Rule::unique('tables', 'name')->withoutTrashed()],
            'capacity' => 'bail|required|numeric|min:1|integer',
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
}
