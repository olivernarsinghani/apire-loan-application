<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;

class AdminAuthController extends BaseController
{
    /**
     * Admin login request to validated the admin
     *
     * @return json
     */
    public function login(AdminLoginRequest $request)
    {
        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'admin']);

            $admin = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
            $success['name'] =  $admin->name;
            $success['token'] =  $admin->createToken('admintokencreated', ['admin'])->accessToken;
            return $this->sendResponse($success, 'Admin login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
        }
    }
}
