<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\Response;

trait RequestErrorMessage
{
    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'max' => ':attribute tidak boleh melebihi :max karakter',
            'exists' => ':attribute tidak dapat ditemukan',
            'numeric' => ':attribute harus berupa angka',
            'string' => ':attribute harus berupa teks',
            'required' => ':attribute wajib diisi',
        ];
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * 
     * @return never
     */
    public function failedValidation(Validator $validator): never
    {
        $errors = [];

        foreach ($validator->errors()->toArray() as $index => $value) {
            $errors[] = [
                'property' => $index,
                'message' => $value[0],
            ];
        }

        throw new HttpResponseException(Response::SetAndGet(Response::BAD_REQUEST, 'Validasi gagal', $errors));
    }
}
