<?php

namespace App\Repositories;

use App\Interfaces\SysCountryInterface;

use App\Models\SysCountry;

class SysCountryRepository extends BaseRepository implements SysCountryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(SysCountry $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $start
     * @param int $length
     * @param array $columns
     * @param array $params
     * @param array $orders
     * @param array $relations
     * @param null $group_column
     * @param string|null $lock
     * @return array|mixed
     */
    public function pagination(int $start, int $length, array $columns = [], array $params = [], array $orders = [], array $relations = [], $group_column = null, string $lock = null)
    {
        $query = $this->model->withTrashed()->with($relations);

        if (!empty($params)) {
            if (isset($params['role'])) {
                $query::role($params['role']);
            }

            if (isset($params['username'])) {
                $query->where('username', 'LIKE', "%{$params['username']}%");
            }

            if (isset($params['name'])) {
                $query->where('name', 'LIKE', "%{$params['name']}%");
            }

            if (isset($params['email'])) {
                $query->where('email', 'LIKE', "{$params['email']}%");
            }

            if (isset($params['mobile_no'])) {
                $query->where('mobile_no', 'LIKE', "{$params['mobile_no']}%");
            }

            if (isset($params['status'])) {
                $query->where('status', $params['status']);
            }
        }

        $query->whereHas('roles', function ($query) use ($params) {
            $query->whereNotIn('guard_name', ['merchant','member']);
        });

        $query->with($relations);
        $total_records = $query->count();
        $order_by = !empty($orders) ? $orders['column'] : 'id';
        $order_dir = !empty($orders) ? $orders['dir'] : 'desc';

        if ($lock) {
            $query->lockForUpdate();
        }

        if ($group_column) {
            $query->groupBy($group_column);
        }

        $query->skip($start)->take($length)->orderBy($order_by, $order_dir)->select($columns);

        return [
            'total' => $total_records,
            'data' => $query->get()
        ];
    }

    /**
     * @param int $length
     * @return mixed|void
     */
    public function setPerPage(int $length)
    {
        $this->model->setPerPage($length);
    }
}
