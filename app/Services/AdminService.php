<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 6:00 PM
 */

namespace App\Services;

use Illuminate\Support\Facades\Hash;

use Auth;
use Carbon\Carbon;

use App\Traits\ResponseAPI;

use App\Interfaces\AdminInterface;
use App\Interfaces\RoleInterface;

class AdminService extends UserService
{
    use ResponseAPI;

    protected $interface;

    public function __construct(
        AdminInterface $interface,
        RoleInterface $roleInterface
    ) {
        parent::__construct($interface, $roleInterface);
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!empty($params['limit'])) {
            $this->interface->setPerPage($params['limit']);
        }

        $limit = $this->interface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->interface->pagination($start, $limit, $columns, $params, $order, $relations);

        return $this->responsePaginate($limit, $result);
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
            $token = $user->createToken('admin', ['role:admin', 'gateway:member', 'product:open', 'profile:update']);
            $success['token'] =  $token->plainTextToken;
            $success['name'] =  $user->name;
            $success['expired_at'] = $token->accessToken->expires_at->format("Y-m-d H:i:s");

            return $this->response(true, 'login_successfully', $success);
        }

        return $this->response(false, 'unauthorized_access');
    }

    /**
     * @param $id
     * @return array
     */
    public function getDetails($id)
    {
        $result = $this->interface->find($id);
        if ($result) {
            return $this->response(true, 'success', $result);
        }

        return $this->response(false, 'invalid_record');
    }

    /**
     * @param string $email
     * @param string $password
     * @param string $role
     * @param string $name
     * @param string $username
     * @param array $param
     * @return array
     */
    public function store(string $username, string $password, string $email, string $name, string $role)
    {
        $result = $this->interface->create([
            'username' => $username,
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'email_verified_at' => Carbon::now()
        ]);

        if ($result) {
            $role = $this->roleInterface->findBy('code', str_replace(" ", "", strtolower($role)), ['*'], [], '');

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
}
