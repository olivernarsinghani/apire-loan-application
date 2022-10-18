<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class InsertLoanRequest extends FormRequest
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
        $arrLoanError['amount'] = 'required|numeric|between:1,99999999999999';
        $arrLoanError['loan_tenure'] = 'required';
        $arrLoanError['user_id'] = 'required|numeric';
        $arrLoanError['interest_rate'] = 'required|numeric|between:0,99.99';
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
            'amount.required' => 'Please enter required loan amount.',
            'amount.numeric' => 'Please enter valid required loan amount.',
            'loan_tenure' => 'Please enter required loan tenure.',
            'user_id' => 'Please enter user id.',
            'interest_rate.required' => 'Please enter interest rate.',
            'interest_rate.numeric' => 'Please enter valid interest rate.',
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
