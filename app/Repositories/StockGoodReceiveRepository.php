<?php

namespace App\Repositories;

use App\Interfaces\StockGoodReceiveInterface;

use App\Models\StockGoodReceive;

class StockGoodReceiveRepository extends BaseRepository implements StockGoodReceiveInterface
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
    public function __construct(StockGoodReceive $model)
    {
        $this->model = $model;
    }
}
