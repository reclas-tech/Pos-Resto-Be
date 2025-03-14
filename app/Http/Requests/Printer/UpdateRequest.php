<?php

namespace App\Http\Requests\Printer;

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
            'checker_ip' => 'bail|required|string|max:20',
            'link' => 'bail|required|string|max:65000',
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
            'checker_ip' => 'IP Printer Checker',
            'link' => 'Link Printer Receiver',
        ];
    }
}
