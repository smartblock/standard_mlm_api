<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;
use DB;

use App\Interfaces\ProductInterface;

use App\Models\Product;

class ProductRepository extends BaseRepository implements ProductInterface
{
    use ResponseAPI;

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
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
            if ($params['group']) {
                $query->where('group', $params['group']);
            }
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->select($columns)->get();
    }
}
