<?php

use App\Http\Controllers\Auth\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('adminLogin',[AdminAuthController::class, 'login']);


Route::group( ['middleware' => ['auth:admin-api','scopes:admin'] ],function(){
    Route::get('pendingloans',[AdminController::class, 'getAllPendingLons']);
    Route::post('loanapproval',[AdminController::class, 'loanApproval']);
 });




//  front routes start

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);

 Route::group( ['middleware' => ['auth:api'] ],function(){
    Route::post('create_loan', [LoanController::class, 'createLoan']);
    Route::post('loanstatus', [LoanController::class, 'customerLoanStatus']);
    Route::post('payschedulerepayment', [LoanController::class, 'payScheduleRepayment']);

});