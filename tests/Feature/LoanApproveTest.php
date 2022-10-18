<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Admin;
use Laravel\Passport\Passport;
use App\Models\User;

class LoanApproveTest extends TestCase
{
   
    /**
     * Create Admin user and store admin access token in global for letter use success case.
     *
     * @return json
     */
    public function test_create_admin_successful_response()
    {
        global $adminToken;
        $this->artisan('passport:install');

        $admin = Admin::factory()->create();
        Passport::actingAs($admin);
 
        $adminToken = $admin->createToken('AdminToken', ['admin'])->accessToken;
        $this->assertTrue(true);
    }


     /**
     * Create user for and store user token and user Id in global use for letter use with success case.
     *
     * @return json
     */
    public function test_create_user_successful_response()
    {
        global $userToken,$userId;
        $this->artisan('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user);
 
        $userToken = $user->createToken('userToken')->accessToken;
        $userId = $user->id;
        $this->assertTrue(true);
    }

    /**
     * Test to create loan for the curren user and store loan Id in global for letter use for success case
     *
     * @return json
     */
    public function test_create_loan_successful_response()
    {
        global  $userToken,$userId,$loanId;
        $headers = ['Authorization' => 'Bearer '.$userToken];
        $payload = ['amount' => 5000, 'loan_tenure' => 3, 'user_id' => $userId,'interest_rate'=>1];
        $response = $this->json('POST', '/api/create_loan', $payload, $headers);
        $loanId = $response->json()['data'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $loanId,
                'message' => 'Loan Created successfully',
           
        ]);
    }

     /**
     * To test create loan for the current user and store loan Id in global for letter use for failed case
     *
     * @return void
     */
    public function test_create_loan_with_wrong_payload_failed_response()
    {
        global  $userToken,$userId,$loanId;
        $headers = ['Authorization' => 'Bearer '.$userToken];
        $payload = ['amount' => 'amount', 'loan_tenure' => 3, 'user_id' => 111111,'interest_rate'=>1];
        $response = $this->json('POST', '/api/create_loan', $payload, $headers);
        $data = $response->json()['data'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'data' => $data,
                'message' => 'Validation errors',
           
        ]);
    }

    /**
     * To test loan approval with success case and retun success assertjson.
     *
     * @return void
     */
    public function test_loan_approve_successful_response()
    {
        global  $userId,$loanId,$adminToken;
        $headers = ['Authorization' => 'Bearer '.$adminToken];
        $payload = ['loan_id' => $loanId, 'user_id' => $userId];
        $response = $this->json('POST', '/api/loanapproval', $payload, $headers);
        $data = $response->json()['data'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $data,
                'message' => 'Loan has been approved',
           
        ]);
    }

      /**
     * To test loan approval with failed case and retun failed assertjson.
     *
     * @return void
     */
    public function test_loan_approve_failed_response()
    {
        global  $userId,$loanId,$adminToken;
        $headers = ['Authorization' => 'Bearer '.$adminToken];
        $payload = ['loan_id' =>221, 'user_id' => 221];
        $response = $this->json('POST', '/api/loanapproval', $payload, $headers);
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Loan Not found',
           
        ]);
    }
}
