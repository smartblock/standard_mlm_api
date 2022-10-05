<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 6:00 PM
 */

namespace App\Services;

use App\Interfaces\SponsorLogInterface;
use App\Jobs\RegistrationOTP;

use Illuminate\Support\Facades\Hash;

use Auth;
use Mail;
use Carbon\Carbon;

use App\Traits\ResponseAPI;

use App\Interfaces\SysCountryInterface;
use App\Interfaces\MemberInterface;
use App\Interfaces\RoleInterface;

class MemberService extends UserService
{
    use ResponseAPI;

    CONST ROLE = "member";

    protected $interface, $countryInterface, $sponsorInterface;
    protected $otpService;

    public function __construct(
        MemberInterface $interface,
        RoleInterface $roleInterface,
        SysCountryInterface $countryInterface,
        OtpService $otpService,
        SponsorLogInterface $sponsorLogInterface
    ) {
        parent::__construct($interface, $roleInterface);
        $this->countryInterface = $countryInterface;
        $this->otpService = $otpService;
        $this->sponsorInterface = $sponsorLogInterface;
    }

    /**
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function authenticate(string $email, string $password)
    {
        $user = $this->interface->findBy('email', $email);
        if (!$user) {
            return $this->response(false, 'invalid_user_credential');
        }

        if (Hash::check($password, $user->password)) {
            $token = $user->createToken('member', ['role:admin', 'gateway:member', 'product:open', 'profile:update']);
            $success['token'] =  $token->plainTextToken;
            $success['name'] =  $user->name;
            $success['expired_at'] = $token->accessToken->expires_at->format("Y-m-d H:i:s");

            return $this->response(true, 'login_successfully', $success);
        }

        return $this->response(false, 'unauthorized_access');
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if ($params['limit']) {
            $this->interface->setPerPage($params['limit']);
        }

        $limit = $this->interface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->interface->pagination($start, $limit, $columns, $params, $order, $relations);

        return $this->responsePaginate($limit, $result);
    }

    /**
     * @param $id
     * @param array $relations
     * @return array
     */
    public function getDetails($id, array $relations = [])
    {
        $result = $this->interface->find($id);
        if ($result) {
            return $this->response(true, 'success', $result);
        }

        return $this->response(false, 'invalid_record');
    }

    /**
     * @param string $country
     * @param string $username
     * @param string $password
     * @param string $email
     * @param array $options
     * @return array
     */
    public function store(string $country, string $sponsor, string $username, string $password, string $email, array $options)
    {
        $validate_username = $this->interface->findBy('username', $username);
        if ($validate_username) {
            return $this->response(false, 'invalid_username');
        }

        $country = $this->countryInterface->findBy('code', $country, ['id']);
        if (!$country) {
            return $this->response(false, 'invalid_country');
        }

        $parent = $this->interface->findBy('code', $sponsor, ['id']);
        if (!$parent) {
            return $this->response(false, 'invalid_sponsor');
        }

        $result = $this->interface->create([
            'code' => "MY{$this->generateCode(6, "MY")}",
            'username' => $username,
            'country_id' => $country['id'],
            'referral_code' => $this->generateReferralCode(16),
            'name' => $options['name'] ?? null,
            'email' => $email,
            'password' => bcrypt($password),
            'parent_id' => $parent['id']
        ]);

        if ($result) {
            $role = $this->roleInterface->findBy('code', self::ROLE, ['*']);
            if (!$role) {
                return $this->response(false, 'invalid_role');
            }

            $role_result = $result->assignRole($role);
            if ($role_result) {
                $otp = $this->otpService->generateOTP($email);
                dispatch(new RegistrationOTP([
                    'email' => $email,
                    'name' => $options['name'],
                    'otp' => $otp['otp_code']
                ]))->delay(Carbon::now()->addSeconds(1));
                return $this->response(true, 'record_saved_successfully');
            }

            return $this->response(false, 'failed_to_insert_role');
        }

        return $this->response(false, 'failed_to_insert');
    }

    /**
     * @param int $id
     * @param string $username
     * @param string $name
     * @param string $email
     * @param string $status
     * @param string $password
     * @param string $role
     * @param array $param
     * @return array
     */
    public function update(int $id, string $username, string $name, string $email, string $status, string $password, string $role, array $param)
    {
        $result = $this->interface->findBy('id', $id, ['*'], [], true);
        if (!$result) {
            return $this->response(false, 'invalid_record');
        }

        $result['username']  = $username;
        $result['name'] = $name;
        $result['email'] = $email;
        $result['status'] = $status;

        if (!empty($password)) {
            $result['password'] = bcrypt($password);
        }

        if ($result->save()) {
            $role = $this->roleInterface->getRoleByCode($role, 'admin');
            if (!$role) {
                return $this->response(false, 'invalid_role');
            }

            $result->roles()->detach();
            $role_result = $result->assignRole($role);

            if ($role_result) {
                return $this->response(true, 'record_updated_successfully');
            }

            return $this->response(false, 'failed_to_insert_role');
        }

        return $this->response(false, 'failed_to_update');
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        $result = $this->interface->find($id);
        if ($result) {
            if ($result->delete()) {
                return $this->response(true, 'record_deleted_successfully');
            }
        }

        return $this->response(false, 'failed_to_delete');
    }

    public function validateSponsor(string $sponsor)
    {
        $detail = $this->interface->findBy('username', $sponsor);
        if ($detail) {
            return $this->response(true, 'success', $detail);
        }

        return $this->response(false, 'invalid_sponsor');
    }

    public function changeSponsor(string $username, string $new_sponsor)
    {
        $user = $this->interface->findBy('username', $username, );
        if (!$user) {
            return $this->response(false, 'invalid_user');
        }

        $old_sponsor = $user->sponsor_id;
        $old_sponsor_username = $user->sponsor->username;
        $new_upline = $this->interface->findBy('username', $new_sponsor);
        if (!$new_upline) {
            return $this->response(false, 'invalid_sponsor');
        }

        $user->parent_id = $new_upline['id'];
        if ($user->save()) {
            $sponsor_result = $this->sponsorInterface->create([
                'user_id' => $user->id,
                'sponsor_id_from' => $old_sponsor,
                'sponsor_from' => $old_sponsor_username,
                'sponsor_id_to' => $new_upline->id,
                'sponsor_to' => $new_upline->username
            ]);
            if ($sponsor_result) {
                return $this->response(true, 'record_saved_successfully');
            }

            return $this->response(false, 'failed_to_save');
        }

        return $this->response(false, 'failed_to_save');
    }
}
