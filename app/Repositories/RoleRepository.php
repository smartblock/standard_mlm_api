<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;

use App\Models\UserRole;

class RoleRepository extends BaseRepository implements RoleInterface
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
    public function __construct(UserRole $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $name
     * @param string $guard_name
     * @return mixed
     */
    public function getRoleByCode(string $code, string $guard_name)
    {
        return $this->model->where('code', $code)
            ->where('guard_name', $guard_name)
            ->first();
    }

    /**
     * @param string $name
     * @param string $guard_name
     * @param int $id
     * @return mixed
     */
    public function validate(string $name, string $guard_name, int $id = null)
    {
        $query = $this->model->with([]);

        if (!empty($id)) {
            $query->where('id', '!=', $id);
        }

        return $query->where('name', $name)
            ->where('guard_name', $guard_name)
            ->first();
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
        $query = $this->model->with($relations);
        if (!empty($params)) {
            if (isset($params['name'])) {
                $query->where('name', 'like', "%{$params['name']}%");
            }
        }

        $total_records = $query->count();
        $order_by = !empty($orders) ? $orders['column'] : 'id';
        $order_dir = !empty($orders) ? $orders['dir'] : 'desc';

        if ($lock) {
            $query->lockForUpdate();
        }

        if ($group_column) {
            $query->groupBy($group_column);
        }

        $data = $query->skip($start)->take($length)->orderBy($order_by, $order_dir)->select($columns);

        return [
            'total' => $total_records,
            'data' => $data->get()
        ];
    }

    /**
     * @param array $columns
     * @param array $relations
     * @param array $params
     * @param array $orders
     * @param string|null $lock
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function all(array $columns = ['*'], array $relations = [], array $params = [], array $orders = [], string $lock = null)
    {
        $query = $this->model->with($relations);

        if (!empty($params)) {
            if (isset($params['root_code'])) {
                if (!empty($params['root_code'])) {
                    $query->where('code', $params['root_code']);
                } else {
                    $query->whereNull('parent_id');
                }
            }

            if (isset($params['parent_code'])) {
                $parent_code = $params['parent_code'];
                $query->whereHas('parent', function ($query) use ($parent_code) {
                    $query->where('code', $parent_code);
                });
            }
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->select($columns)->get();
    }
}
