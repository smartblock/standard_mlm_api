<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 09/02/2022
 * Time: 4:42 PM
 */

namespace App\Services\Auth;

use DB;
use Carbon\Carbon;
use Auth;
use Hash;

use App\Traits\ResponseAPI;

use App\Interfaces\UserInterface;
use App\Interfaces\RoleInterface;

use App\Services\OtpService;

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

        if (empty($user->email_verified_at)) {
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

    /**
     * Submit reset password request
     *
     * @param string $email
     * @return array
     */
    public function resetPassword(string $email)
    {
        $user = $this->interface->findBy('email', $email);
        if (!$user) {
            return $this->response(false, 'invalid_user');
        }

        if (empty($user['email_verified_at'])) {
            return $this->response(false, 'please_verify_account_first');
        }

        $token = Str::random(64);
        $date_now = Carbon::now();
        $result = $this->passwordResetInterface->create([
            'email' => $email,
            'token' => $token,
            'created_at' => $date_now,
            'expired_at' => $date_now->addMinutes(5)
        ]);

        if (!$result) {
            return $this->response(false, 'failed_to_send_mail');
        }

        dispatch(new ForgotPassword([
            'email' => $email,
            'name' => $options['name'] ?? null,
            'token' => $token
        ]))->delay(Carbon::now()->addSeconds(5));

        return  $this->response(true, 'please_check_mailbox');
    }

    /**
     * @param string $token
     * @param string $password
     * @param string $password_confirmation
     * @return array
     */
    public function resetPasswordSubmission(string $token, string $password, string $password_confirmation)
    {
        $password_token = $this->passwordResetInterface->findBy('token', $token, ['*'], ['user']);

        if ($password_token && $password_token['status'] == 0) {
            return $this->response(false, 'invalid_token');
        }

        $date_now = Carbon::now();
        if ($password_token['expired_at'] < $date_now) {
            return $this->response(false, 'token_expired');
        }

        if ($password != $password_confirmation) {
            return $this->response(false, 'invalid_password');
        }

        $password_token->user->password = bcrypt($password);
        if ($password_token->user->save()) {
            $password_token->status = 0;
            $password_token->save();

            return $this->response(true, 'updated_successfully');
        }

        return $this->response(false, 'failed_to_updated_password');
    }
}