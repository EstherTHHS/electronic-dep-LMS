<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Actions\Auth\APILoginAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $loginType = ($request->phone) ? "phone" : "email";
        $identity = ($request->phone) ? $request->phone : $request->email;

        $loginResponse = (new APILoginAction($loginType, $identity, $request->password, User::class))->run("user_token");

        if ($loginResponse["code"] != 200) {
            ResponseMessage($loginResponse["message"], 401);
        } else {
            // $loginResponse['user']['roles'] = $loginResponse['user']->getRoleNames();
            ResponseData($loginResponse);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        ResponseMessage("Logout success");
    }
}
