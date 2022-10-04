<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 6:00 PM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use DB;

use App\Interfaces\UserInterface;
use App\Interfaces\RoleInterface;

class UserService extends BaseService
{
    use ResponseAPI;

    protected $interface, $roleInterface;

    public function __construct(
        UserInterface $interface,
        RoleInterface $roleInterface
    )
    {
        $this->roleInterface = $roleInterface;

        parent::__construct($interface);
    }

    public function save(string $username, string $password, string $name, string $email, string $role)
    {
        $result = $this->interface->create([
            'username' => $username,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        if ($result) {
            $role = $this->roleInterface->findBy('code', str_replace(" ", "", strtolower($role)));
            if (!$role) {
                return $this->response(false, 'invalid_role');
            }

            $role_result = $result->assignRole($role);
            if ($role_result) {
                return $this->response(true, 'record_saved_successfully');
            }

            return $this->response(false, 'failed_to_insert_role');
        }

        return $this->response(false, 'failed_to_insert');
    }

    public function generateReferralCode($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);

        $code = '';

        while (strlen($code) < $length) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        $referral = $this->interface->findBy('referral_code', $code, ['id']);
        if ($referral) {
            $this->generateUniqueCode($length);
        }

        return $code;
    }

    public function generateCode($length = 8, string $prefix)
    {
        $characters = '0123456789';
        $charactersNumber = strlen($characters);

        $code = '';

        while (strlen($code) < $length) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        $referral = $this->interface->findBy('code', "{$prefix}{$code}", ['id']);
        if ($referral) {
            $this->generateCode($length);
        }

        return $code;
    }
}
