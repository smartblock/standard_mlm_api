<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 09/02/2022
 * Time: 4:42 PM
 */

namespace App\Services\Auth;

use DB;
use Auth;
use Hash;

use App\Traits\ResponseAPI;

use App\Services\BaseService;

use App\Interfaces\AdminInterface;
use App\Interfaces\RoleInterface;

class AuthService extends BaseService
{
    use ResponseAPI;

    protected $interface, $roleInterface;

    public function __construct(
        AdminInterface $interface,
        RoleInterface $roleInterface
    )
    {
        $this->interface = $interface;
        $this->roleInterface = $roleInterface;
    }

    /**
     * @param string $gateway
     * @param string $username
     * @param string $password
     * @return array
     */
    public function authenticate(string $gateway, string $username, string $password)
    {
        $user = $this->interface->findBy('username', $username);
        if (!$user) {
            return $this->response(false, 'invalid_user_credential');
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
