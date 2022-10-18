<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Services\LoanService;
use App\Http\Requests\InsertLoanRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\InsertScheduledRepayment;
use App\Http\Requests\LoanStatusRequest;


class LoanController extends BaseController
{

    protected $loanservice = '';

    public function __construct(LoanService $loanservice)
    {
        $this->loanservice = $loanservice;
    }

    /**
     * create loan request 
     *
     * @return json Response
     */

    public function createLoan(InsertLoanRequest $request)
    {
        $input = $request->all();
        try {

            //check user has already pending or unpaid loan
            $loanExists = $this->loanservice->get_loan_info($input);
            if(!$loanExists === null){
                return $this->sendError('You already have a pending or unpaid loan.', [], 200);
            }

            // create a  new loan
            $result =  $this->loanservice->create_loan($input);
            
            return $this->sendResponse($result['id'], 'Loan Created successfully');
        } catch (QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return  $this->sendError($exception->getMessage(), $errorInfo, 500);
        }
    }


    /**
     * get customer loan status 
     *
     * @return json Response
     */

    public function customerLoanStatus(LoanStatusRequest $request)
    {
        $input = $request->all();
        try {
            $result =  $this->loanservice->get_customer_loan_status($input);
            if ($result) {
                return $this->sendResponse($result, 'Loan Data Found');
            } else {
                return $this->sendResponse('', 'Loan Data Not Found');
            }
        } catch (QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return  $this->sendError($exception->getMessage(), $errorInfo, 500);
        }
    }

        /**
     * get customer loan status 
     *
     * @return json Response
     */

    public function payScheduleRepayment(InsertScheduledRepayment $request)
    {
        $input = $request->all();
      
        try {
            $result =  $this->loanservice->pay_schedule_repayment($input);
            if ($result) {
                return $this->sendResponse($result, 'Payment has been made successfully');
            } else {
                return $this->sendResponse('',$result);
            }
        } catch (QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return  $this->sendError($exception->getMessage(), $errorInfo, 500);
        }
    }
}
