<?php

namespace App\Http\Requests\Module;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class EditModule extends BaseRequest
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
            'name'          => 'required|max:255',
            'route'         => [
                'required',
                'max:255',
                Rule::unique('modules', 'route')->ignore($idValue, 'uuid'),
            ],
        ];
    }
}
