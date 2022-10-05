<?php

namespace App\Repositories;

use App\Interfaces\AnnouncementDetailInterface;

use App\Models\AnnouncementDetail;

class AnnouncementDetailRepository extends BaseRepository implements AnnouncementDetailInterface
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
    public function __construct(AnnouncementDetail $model)
    {
        $this->model = $model;
    }
}
