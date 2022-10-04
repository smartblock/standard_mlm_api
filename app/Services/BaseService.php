<?php

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\EloquentRepositoryInterface;

class BaseService
{
    use ResponseAPI;

    protected $interface;

    public function __construct(EloquentRepositoryInterface $interface)
    {
        $this->interface = $interface;
    }

    public function listAll(array $columns = ['*'], array $relations = [], array $params = [], array $orders = [], string $lock = null)
    {
        return $this->interface->all($columns, $relations, $params, $orders, $lock);
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
