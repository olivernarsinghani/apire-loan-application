<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class LoanStatusTest extends TestCase
{
    /**
     * Test Create a new loan with success case.
     *
     * @return json
     */

    public function test_create_loan_successful_response()
    {
        global $loanId, $userId, $userToken;
        $this->artisan('passport:install');

        $user = User::factory()->create();
        $userId = $user->id;
        Passport::actingAs($user);

        $userToken = $user->createToken('TestToken')->accessToken;

        $headers = ['Authorization' => 'Bearer ' . $userToken];
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
     * Test Create a new loan with by adding the wrong payload fail case.
     *
     * @return json
     */
    public function test_create_loan_with_wrong_paload_failed_response()
    {
        global $loanId;
        $this->artisan('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user);

        $token = $user->createToken('Token')->accessToken;

        $headers = ['Authorization' => 'Bearer ' . $token];
        $payload = ['amount' => 'amount', 'loan_tenure' => '3', 'user_id' => $user->id,'interest_rate'=>1];
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
     * Test to check the loan statuc API with success case.
     *
     * @return json
     */
    public function test_loan_status_successful_response()
    {
        global $loanId, $userId, $userToken;

        $headers = ['Authorization' => 'Bearer ' . $userToken];
        $payload = ['loan_id' => $loanId, 'user_id' => $userId];
        $response = $this->json('POST', '/api/loanstatus', $payload, $headers);
        $data = $response->json()['data'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $data,
                'message' => 'Loan Data Found',

            ]);
    }

    /**
     * Test to check the loan statuc API with failed case.
     *
     * @return json
     */
    public function test_loan_status_failed_response()
    {
        global $loanId, $userId, $userToken;

        $headers = ['Authorization' => 'Bearer ' . $userToken];
        $payload = ['loan_id' => 'dd', 'user_id' => 441];
        $response = $this->json('POST', '/api/loanstatus', $payload, $headers);
        $data = $response->json()['data'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'data' => $data,
                'message' => 'Validation errors',

            ]);
    }
}
