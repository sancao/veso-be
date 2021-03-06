<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;

class UpdateModule extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'id'            => 'required|max:255',
            'module_list'   => 'required|json',
        ];
    }
}
