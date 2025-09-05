<?php

namespace App\Actions\Auth;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class APILoginAction
{
    /**
     * Create a new class instance.
     */
    private $credential_name;
    private $identity;
    private $password;
    private $credential_type;

    public function __construct(string $credential_name, string $identity, string $password, string $credential_type)
    {
        $this->credential_name = $credential_name;
        $this->identity = $identity;
        $this->password = $password;
        $this->credential_type = $credential_type;
    }

    public function run(string $token_name, array $abilities = []): array
    {
        $user = $this->credential_type::where($this->credential_name, $this->identity)->first();

        $login_response = [
            "token" => null,
            "code" => 401,
            "success" => false,
            "message" => null
        ];

        if (!isset($user)) {
            $login_response["message"] = "User not found";
            return $login_response;
        }
        if (!Hash::check($this->password, $user->password)) {   
            $login_response["message"] = "Password not match";
            return $login_response;
        }

        $login_response["user"] = $user->only([
            'id',
            'name',
            'email',
            'phone',
            'qualification',
            'birth_date',
            'address',
            'is_active',
            'image_path',
            'image_url',
            'created_at',
            'updated_at',
        ]);
        if ($abilities) {
            $token = $user->createToken($token_name, $abilities)->plainTextToken;
        } else {
            $token = $user->createToken($token_name)->plainTextToken;
        }
        $login_response["token"] = $token;
        // $login_response["role"] = optional($user->roles->first())->name;
        $login_response["code"] = 200;
        $login_response["success"] = true;
        $login_response["roles"] = $user->getRoleNames();
        $login_response["permissions"] = $user->getAllPermissions()->pluck('name');
        $login_response["message"] = 'Authenticated';
        return $login_response;
    }
}
