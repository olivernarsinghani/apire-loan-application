<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class InsertScheduledRepayment extends FormRequest
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
        $arrLoanError['loan_id'] = 'required|numeric';
        $arrLoanError['user_id'] = 'required|numeric';
        $arrLoanError['repayment_method'] = 'required';
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
            'loan_id' => 'Please enter Loan Id.',
            'user_id' => 'Please enter user Id.',
            'repayment_method' => 'Please enter Payment method.'
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
