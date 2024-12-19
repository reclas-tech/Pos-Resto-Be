<?php

namespace App\Http\Requests\Example;

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
            'user_id' => 'bail|nullable|numeric|exists:users,id',
            'description' => 'bail|required|string|max:65000',
            'code' => 'bail|required|string|max:255',
            'name' => 'bail|required|string|max:255',
            'tag' => 'bail|nullable|string|max:100',
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
            'description' => 'Deskripsi',
            'user_id' => 'Pengguna',
            'code' => 'Kode',
            'name' => 'Nama',
            'tag' => 'Tag',
        ];
    }
}
