<?php

namespace App\Repositories;

use App\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements EloquentRepositoryInterface
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
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $attributes
     * @return \App\Interfaces\Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param int $modelId
     * @param array $attributes
     * @return mixed
     */
    public function update(int $model_id, array $attributes)
    {
        return $this->model->find($model_id)->update($attributes);
    }

    /**
     * @param string $field
     * @param $value
     * @param array $attributes
     * @return mixed
     */
    public function updateBy(string $field, $value, array $attributes)
    {
        return $this->model->where($field, $value)->update($attributes);
    }

    /**
     * @param int $modelId
     * @return bool|void
     */
    public function deleteById(int $model_id)
    {
        return $this->model->where('id', $model_id)->delete();
    }

    /**
     * @param int $id
     * @param boolean $lock
     * @return \App\Interfaces\Model
     */
    public function find(int $id, string $lock = null)
    {
        $query = $this->model->find($id);

        if ($query && $lock) {
            $query->lockForUpdate();
        }

        return $query;
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
        $query = $this->model->with($relations)
            ->where($field, $value);

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first($columns);
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
            foreach($params as $x => $val) {
                $query->where($x, $val);
            }
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        if (!empty($orders)) {
            $query->orderBy($orders['column'], $orders['dir']);
        }

        return $query->select($columns)->get();
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
            foreach($params as $x => $val) {
                $query->where($x, $val);
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
     * @param $relations
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    /**
     * @return int
     */
    public function perPage()
    {
        return $this->model->getPerPage();
    }

    /**
     * @param int $length
     * @return Model|mixed
     */
    public function setPerPage(int $length)
    {
        return $this->model->setPerPage($length);
    }
}
