<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $arrRegiterErrorDetails = array();
        $arrRegiterErrorDetails['name'] = 'required|min:4';
        $arrRegiterErrorDetails['email'] = 'required|email|unique:users,email,'.$this->id;
        $arrRegiterErrorDetails['password'] = 'required|min:8';
        return $arrRegiterErrorDetails;
    }


    /**
     * Get the validation rules messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'Please enter user name',
            'name.min' => 'User name should be atleast 4 character',
            'email.required' => 'Please enter user email',
            'email.email' => 'Please enter valid email address',
            'password.required' => 'Please enter password',
            'password.min' => 'Password should be atleast 8 character'
        ];
    }

    /**
     * Get the validation rules messages error that apply to the request.
     *
     * @return array<string, mixed>
     */
    
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ],422));
    }
}
