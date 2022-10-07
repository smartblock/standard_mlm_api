<?php

namespace App\Repositories;

use App\Interfaces\SysLanguageInterface;

use App\Models\SysLanguage;

class SysLanguageRepository extends BaseRepository implements SysLanguageInterface
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
    public function __construct(SysLanguage $model)
    {
        $this->model = $model;
    }

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
        $query = $this->model->with($relations);
        if (!empty($params)) {
            if (isset($params['code'])) {
                $query->where('code', 'like', "%{$params['code']}%");
            }

            if (isset($params['name'])) {
                $query->where('name', 'like', "%{$params['name']}%");
            }

            if (isset($params['status'])) {
                $query->where('status', $params['status']);
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

    public function getDefaultLanguage()
    {
        return $this->model->where('is_default', 1)
            ->first();
    }
}
