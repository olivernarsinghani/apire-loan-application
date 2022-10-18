<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Services\LoanService;
use Illuminate\Database\QueryException;
use App\Http\Requests\LoanApproavalRequest;

class AdminController extends BaseController
{

    protected $loanservice = '';

    public function __construct(LoanService $loanservice)
    {
        $this->loanservice = $loanservice;
    }
    /**
     * get all pending loan 
     *
     * @return json Response
     */

    public function getAllPendingLons()
    {
        try {
            $pendingLoanData =  $this->loanservice->get_pending_loans();
            return $this->sendResponse($pendingLoanData, 'Loan data');
        } catch (QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return  $this->sendError($exception->getMessage(), $errorInfo, 500);
        }
    }


    /**
     * Approve user loan 
     *
     * @return json Response
     */

    public function loanApproval(LoanApproavalRequest $request)
    {
    
        $input = $request->all();
        try {
            $loan =  $this->loanservice->loan_approval($input);
            if($loan){
                return $this->sendResponse($loan, 'Loan has been approved');
            }else{
                return  $this->sendError('Loan Not found',[] , 200);
            }
        } catch (QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return  $this->sendError($exception->getMessage(), $errorInfo, 500);
        }
    }
}
