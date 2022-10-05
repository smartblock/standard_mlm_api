<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 11:32 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\RoleInterface;

class RoleService extends BaseService
{
    use ResponseAPI;

    protected $roleInterface;

    public function __construct(RoleInterface $interface)
    {
        parent::__construct($interface);
    }

    public function tree(array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!isset($params['parent_code'])) {
            return $this->interface->all($columns, [], [
                'root_code' => ''
            ], $order);
        }

        return $this->interface->all($columns, $relations, $params, $order);
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!empty($params['limit'])) {
            $this->interface->setPerPage($params['limit']);
            unset($params['limit']);
        }

        $limit = $this->interface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->interface->pagination($start, $limit, $columns, $params, $order, $relations, [], true);

        return $this->responsePaginate($limit, $result);
    }

    /**
     * @param string $name
     * @param string $guard_name
     * @param int $seq_no
     * @param int $parent_id
     * @return array
     */
    public function store(string $parent_code, string $name, string $guard_name, int $seq_no)
    {
        $validate = $this->interface->validate($name, $guard_name);
        if ($validate) {
            return $this->response(false, 'role_already_exist');
        }

        $code = str_replace(" ", "", strtolower($name));
        $parent = $this->interface->findBy('code', $parent_code);
        if (!$parent) {
            return $this->response(false, 'invalid_parent_code');
        }

        $result = $this->interface->create([
            'code' => $code,
            'name' => $name,
            'seq_no' => $seq_no,
            'guard_name' => $guard_name,
            'parent_id' => $parent['id'],
            'status' => 'A'
        ]);

        if (!$result) {
            return $this->response(false, 'failed_to_create_record');
        }

        return $this->response(true, 'record_created_successfully');
    }

    /**
     * @param $name
     * @return array
     */
    public function getDetails($name)
    {
        $result = $this->interface->findBy('code', $name, ['*'], ['children']);
        if ($result) {
            return $this->response(true, 'success', $result);
        }

        return $this->response(false, 'invalid_record');
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $seq_no
     * @return array
     */
    public function update(int $id, string $parent_code, string $name, int $seq_no, string $guard_name)
    {
        $parent = $this->interface->findBy('code', $parent_code);
        if (!$parent) {
            return $this->response(false, 'invalid_parent_code');
        }

        $result = $this->interface->findBy('id', $id, ['*'], [], true);
        if (!$result) {
            return $this->response(false, 'invalid_record');
        }

        $result['name'] = $name;
        $result['seq_no'] = $seq_no;
        $result['guard_name'] = $guard_name;
        $result['parent_id'] = $parent['id'];

        if ($result->save()) {
            return $this->response(true, 'record_updated_successfully');
        }

        return $this->response(false, 'failed_to_update');
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        $result = $this->interface->deleteById($id);
        if ($result) {
            return $this->response(true, 'record_deleted_successfully');
        }

        return $this->response(false, 'failed_to_delete');
    }
}
