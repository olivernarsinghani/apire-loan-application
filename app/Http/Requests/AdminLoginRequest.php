<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AdminLoginRequest extends FormRequest
{
   /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $arrLoginErrorDetails = array();
        $arrLoginErrorDetails['email'] = 'required|email';
        $arrLoginErrorDetails['password'] = 'required|min:8';
        return $arrLoginErrorDetails;
    }


    /**
     * Get the validation rules messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'email.required' => 'Please enter admin email',
            'email.email' => 'Please enter valid email address',
            'password.required' => 'Please enter password',
            'password.min' => 'Password must be 8 character'
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
