<?php

namespace App\Repositories;

use App\Interfaces\StockSupplierInterface;

use App\Models\StockSupplier;

class StockSupplierRepository extends BaseRepository implements StockSupplierInterface
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
    public function __construct(StockSupplier $model)
    {
        $this->model = $model;
    }
}
