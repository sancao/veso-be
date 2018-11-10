<?php

namespace App\Http\Requests\Module;

use App\Http\Requests\BaseRequest;

class AddModule extends BaseRequest
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
            'route'         => 'required|max:255|unique:modules,route',
            'name'          => 'required|max:255',
        ];
    }
}
