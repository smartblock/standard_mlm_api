<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;

use App\Interfaces\ProductVariantInterface;

use App\Models\ProductVariant;

class ProductVariantRepository extends BaseRepository implements ProductVariantInterface
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
    public function __construct(ProductVariant $model)
    {
        $this->model = $model;
    }
}
