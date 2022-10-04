<?php

namespace App\Repositories;

use App\Interfaces\SysDocNoInterface;

use App\Models\SysDocNo;

class SysDocNoRepository extends BaseRepository implements SysDocNoInterface
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
    public function __construct(SysDocNo $model)
    {
        $this->model = $model;
    }
}
