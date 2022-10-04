<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 09/02/2022
 * Time: 4:42 PM
 */

namespace App\Services\Auth;

use App\Services\OtpService;
use DB;
use Auth;
use Hash;

use App\Traits\ResponseAPI;

use App\Interfaces\UserInterface;
use App\Interfaces\RoleInterface;

class AuthMemberService extends AuthService
{
    use ResponseAPI;

    protected $interface, $roleInterface;
    protected $otpService;

    public function __construct(
        UserInterface $interface,
        RoleInterface $roleInterface,
        OtpService $otpService
    )
    {
        $this->interface = $interface;
        $this->roleInterface = $roleInterface;
        $this->otpService = $otpService;
    }

    /**
     * @param string $gateway
     * @param string $username
     * @param string $password
     * @return array
     */
    public function authenticate(string $gateway, string $username, string $password)
    {
        $user = $this->interface->findBy('username', $username, ['*']);
        if (!$user) {
            return $this->response(false, 'invalid_user_credential');
        }

        if ($user->roles[0]['code'] != 'member') {
            return $this->response(false, 'invalid_role_access');
        }

        if (empty($user->verify_emailt_at)) {
            $otp = $this->otpService->sendOtp($user['email']);
            return $this->response(false, 'please_verify_email_first');
        }

        if (Hash::check($password, $user->password)) {
            $token = $user->createToken($gateway, ["role-{$gateway}"]);
            $success['token'] =  $token->plainTextToken;
            $success['name'] =  $user->name;
            $success['expired_at'] = $token->accessToken->expires_at;

            return $this->response(true, 'login_successfully', $success);
        }

        return $this->response(false, 'unauthorized_access');
    }
}