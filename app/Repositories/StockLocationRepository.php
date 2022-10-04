<?php

namespace App\Repositories;

use App\Interfaces\StockLocationInterface;

use App\Models\StockLocation;

class StockLocationRepository extends BaseRepository implements StockLocationInterface
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
    public function __construct(StockLocation $model)
    {
        $this->model = $model;
    }
}
