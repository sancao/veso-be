<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
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
            // 'name'          => 'required|max:255',
            // 'email'         => 'required|email|max:255|unique:users,email',
            // 'password'      => 'required|min:6',
            // 'username'      => 'required|min:2'
        ];
    }

    /**
     * Custom returned messages
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'     => 'Name is required',
            'name.max'          => 'Name\'s length under 255',

            'email.required'    => 'Email is required',
            'email.max'         => 'Email\'s length under 255',
            'email.email'       => 'Email is not valid',
            'email.unique'      => 'This email was existed',

            'password.required' => 'Password is required',
            'password.min'      => 'Password at least 6 characters',

            'username.required' => 'Username is required',
            'username.min'      => 'Username at least 2 characters'
        ];
    }
}
