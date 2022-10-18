<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class UserLoginTest extends TestCase
{
    /**
     * User loin feature test case for success responce.
     *
     * @return json
     */
    public function test_login_user_successful_response()
    {
        global $loanId, $userId, $userToken;
        $this->artisan('passport:install');

        $user = User::factory()->create();
       
        $headers = ['Authorization' => 'Bearer ' . $userToken];
        $payload = ['email' => $user->email, 'password' => 'password'];
        $response = $this->json('POST', '/api/login', $payload, $headers);
        $data = $response->json()['data'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $data,
                'message' => 'User login successfully.',

            ]);
    }


    /**
     * User loin feature test case for failed responce.
     *
     * @return void
     */

    public function test_login_user_failed_response()
    {
        global $loanId, $userId, $userToken;
        $this->artisan('passport:install');

        $user = User::factory()->create();
       
        $headers = ['Authorization' => 'Bearer ' . $userToken];
        $payload = ['email' => $user->email, 'password' => 'wrongpassword'];
        $response = $this->json('POST', '/api/login', $payload, $headers);
        $data = $response->json()['data'];
        $response
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'data' => $data,
                'message' => 'Unauthorised.',

            ]);
    }
}
