<?php

namespace App\Repositories;

use App\Interfaces\SponsorLogInterface;

use App\Models\SponsorLog;

class SponsorLogRepository extends BaseRepository implements SponsorLogInterface
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
    public function __construct(SponsorLog $model)
    {
        $this->model = $model;
    }
}
