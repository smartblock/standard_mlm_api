<?php

namespace App\Repositories;

use App\Interfaces\SettingInterface;

use App\Models\SysGeneral;

class SettingRepository extends BaseRepository implements SettingInterface
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
    public function __construct(SysGeneral $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $type
     * @param string $code
     * @return mixed
     */
    public function validate(string $type, string $code)
    {
        return $this->model->where('type', $type)
            ->where('code', $code)
            ->first();
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
            if (isset($params['status'])) {
                $query->where('status', $params['status']);
            }
        }

        $order_by = !empty($orders) ? $orders['column'] : 'id';
        $order_dir = !empty($orders) ? $orders['dir'] : 'desc';

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->orderBy($order_by, $order_dir)->select($columns)->get();
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
            if (isset($params['type'])) {
                $query->where('type', $params['type']);
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
     * @param string $field
     * @param string $value
     * @param array $columns
     * @param array $relations
     * @param string $lock
     * @return mixed
     */
    public function findBy(string $field, string $value, array $columns = ['*'], array $relations = [], string $lock = null)
    {
        $query = $this->model->withTrashed()->with($relations)
            ->where($field, $value);

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first($columns);
    }
}
