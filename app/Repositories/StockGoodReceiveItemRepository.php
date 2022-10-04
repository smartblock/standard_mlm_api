<?php

namespace App\Repositories;

use App\Interfaces\StockGoodReceiveItemInterface;

use App\Models\StockGoodReceiveItem;

class StockGoodReceiveItemRepository extends BaseRepository implements StockGoodReceiveItemInterface
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
    public function __construct(StockGoodReceiveItem $model)
    {
        $this->model = $model;
    }
}
