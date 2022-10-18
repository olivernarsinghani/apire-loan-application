<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;

class LoanCreateTest extends TestCase
{
    /**
     * Test Create a new loan with success case.
     *
     * @return void
     */
    public function test_create_loan_successful_response()
    {
        global $loanId;
        $this->artisan('passport:install');

        $user = User::factory()->create();
        Passport::actingAs($user);

        $token = $user->createToken('TestToken')->accessToken;

        $headers = ['Authorization' => 'Bearer '.$token];
        $payload = ['amount' => 5000, 'loan_tenure' => 3, 'user_id' => $user->id,'interest_rate'=>1];
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

    
}
