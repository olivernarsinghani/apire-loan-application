<?php

namespace App\Http\Services;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Support\Carbon;


class LoanService
{

    /**
     * create new loan request.
     *
     * @return array<string, mixed>
     */
    public function create_loan($input)
    {
        return  loan::create($input);
    }


    /**
     * get user loan info.
     *
     * @return array<string, mixed>
     */
    public function get_loan_info($input)
    {
        $loan = Loan::where('user_id', $input['user_id'])->status('pending')->orWhere('status', 'approved')->first();
        return  $loan;
    }

    /**
     * create new loan request.
     *
     * @return array<string, mixed>
     */
    public function get_pending_loans()
    {
        return Loan::status('pending')->get();
    }

    /**
     * approve pending loan for each user.
     *
     * @return array<string, mixed>
     */
    public function loan_approval($input)
    {
        $loan = Loan::where('id', $input['loan_id'])->where('user_id', $input['user_id'])->status('pending')->first();
        if ($loan) {

            // calculate total interest and update total payble amount
            $totalInterest = $loan->amount * ($loan->interest_rate * $loan->loan_tenure / 100);
            $totalAmountPayble = $loan->amount + $totalInterest;
            $weeklyAmountPayble = number_format($totalAmountPayble / $loan->loan_tenure, 2, '.', '');

            $loan->status = 'approved';
            $loan->payble_amount = $totalAmountPayble;
            $loan->save();


            // add repayment scheuled data start by next week.
            for ($i = 1; $i <= ($loan->loan_tenure); $i++) {
                $days = 7 * $i;
                $repaymentDate  = Carbon::now()->addDays($days)->format('Y-m-d');
                $scheduleData = array(
                    'loan_id' => $loan->id,
                    'repayment_amount' => $weeklyAmountPayble,
                    'repayment_date' => $repaymentDate,
                    'status' => 'pending',
                );
                Repayment::create($scheduleData);
            }

            return $loan;
        }
        return null;
    }

    /**
     * get customer loan status.
     *
     * @return array<string, mixed>
     */
    public function get_customer_loan_status($input)
    {
        return Loan::with('repayments')
                    ->where('user_id', $input['user_id'])
                    ->where('id',$input['loan_id'])
                    ->get();
    }

    /**
     * make rescheduled repayment data.
     *
     * @return array<string, mixed>
     */
    public function pay_schedule_repayment($input)
    {
        // if approved but not fully repaid loan found, proceed to create a repayment for that loan

        $loan = Loan::with(['repayments' => function ($query)  {
                    $query->where('scheduled_repayment.status','pending');
                    $query->first();
                }])
                ->where('id', $input['loan_id'])
                ->where('user_id', $input['user_id'])
                ->status('approved')
                ->where('payble_amount', '!=', '0')
                ->first();
               
            
        if ($loan && count($loan->repayments) > 0) {
            if ($input['amount'] >= $loan->repayments[0]['repayment_amount']) {
                // calculate pending amount 
                 $pendingAmount = $loan->payble_amount - $input['amount'];
                  
                 if($pendingAmount <  0 ){
                    return 'your pending scheduled repayment amount is only $'.$loan->repayments[0]['repayment_amount'];
                 }
                // if payble amount is grether then the scheduled amount then change the scheduled repayment amount as per the new amount
                if ($input['amount'] > $loan->repayments[0]['repayment_amount']) {
                    
                        // get all scheduled payment accept the current one
                      $getScheduledRepaymentData =   Repayment::where('id',"!=",$loan->repayments[0]['id'])
                                                                ->where('loan_id',$input['loan_id'])
                                                                ->status('pending')
                                                                ->pluck('id');
                      if(count($getScheduledRepaymentData) > 0){

                      $newScheduledRepaymentAmount = $pendingAmount / count($getScheduledRepaymentData);
                        // update new repayment amount
                        foreach($getScheduledRepaymentData as $scheduledrepayment){
                            Repayment::where('id', $scheduledrepayment)->update(['repayment_amount' => $newScheduledRepaymentAmount]);
                        }
                      }
                }

                $repayment = Repayment::find($loan->repayments[0]['id']);
                $repayment->status = 'paid';
                $repayment->repayment_method = $input['repayment_method'];
                $repayment->save();

               
                $loan->payble_amount = $pendingAmount;

                // check if the pending amount is 0 then update loan status as paid
                if ($pendingAmount == 0) {
                    $loan->status = 'paid';
                }
                $loan->save();

                return $loan;
            } else {
                $return = 'You must pay a repayment amount of $' . $loan->repayments[0]['repayment_amount'];
            }
        }else{
            $return = 'No unpaid loan found to make a repayment.';
        }
        
        return $return;
    }
}
