<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;
use DB;

use App\Interfaces\ProductPackageItemInterface;

use App\Models\ProductPackageItem;

class ProductPackageItemRepository extends BaseRepository implements ProductPackageItemInterface
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
    public function __construct(ProductPackageItem $model)
    {
        $this->model = $model;
    }
}
