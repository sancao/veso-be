<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class EditRole extends BaseRequest
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
        //---------------------------------------
        // Use to ignore current update record
        //---------------------------------------
        $idValue    =   $this->get('uuid');
        //---------------------------------------

        return [
            'uuid'          => 'required',
            'name'         => [
                'required',
                'max:255',
                Rule::unique('roles', 'name')->ignore($idValue, 'uuid'),
            ],
        ];
    }
}
