<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Return error messages
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        $json = [
            'status' => false,
            'message' => $validator->errors()->all(':message'),
        ];
        throw new HttpResponseException(response()->json($json, 422));
    }
}
