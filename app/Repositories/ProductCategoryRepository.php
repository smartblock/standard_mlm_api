<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;
use DB;

use App\Interfaces\ProductCategoryInterface;

use App\Models\ProductCategory;

class ProductCategoryRepository extends BaseRepository implements ProductCategoryInterface
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
    public function __construct(ProductCategory $model)
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
            if (isset($params['parent_code'])) {
                if (!empty($params['parent_code'])) {
                    $code = $params['parent_code'];
                    $query->whereHas('parent', function ($q) use ($code) {
                        $q->where('category_code', $code);
                    });
                } else {
                    $query->whereHas('parent', function ($q) {
                        $q->where('category_code', "Root");
                    });
                }
            }
        } else {
            $query->whereHas('parent', function ($q) {
                $q->where('category_code', "Root");
            });
        }

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->select($columns)->get();
    }
}
