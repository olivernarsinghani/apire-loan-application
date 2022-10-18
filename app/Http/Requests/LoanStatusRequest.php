<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoanStatusRequest extends FormRequest
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
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $arrLoanError = array();
        $arrLoanError['user_id'] = 'required|numeric';
        $arrLoanError['loan_id'] = 'required|numeric';
        return $arrLoanError;
    }

    /**
     * Get the validation rules messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'user_id.required' => 'Please enter user id.',
            'user_id.numeric' => 'Please enter valid user id.',
            'loan_id.required' => 'Please enter loan id.',
            'loan_id.numeric' => 'Please enter valid loan id.',
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
        ]));
    }
}
