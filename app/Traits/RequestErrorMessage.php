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
            'required_if' => ':attribute wajib diisi jika :other bernilai :value',
            'required_without' => ':attribute atau :values wajib diisi',
            'min' => ':attribute tidak boleh kurang dari :min karakter',
            'max' => ':attribute tidak boleh melebihi :max karakter',
            'integer' => ':attribute harus berupa bilangan bulat',
            'distinct' => ':attribute tidak boleh terduplikasi',
            'in' => ':attribute tidak valid (pilihan: :values)',
            'array' => ':attribute harus berupa larik / array',
            'exists' => ':attribute tidak dapat ditemukan',
            'email' => ':attribute harus berformat email',
            'numeric' => ':attribute harus berupa angka',
            'digits' => ':attribute harus :digits digit',
            'required_with' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'unique' => ':attribute telah digunakan',
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
