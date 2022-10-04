<?php

namespace App\Repositories;

use App\Interfaces\AnnouncementInterface;

use App\Models\Announcement;

class AnnouncementRepository extends BaseRepository implements AnnouncementInterface
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
    public function __construct(Announcement $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function validate(string $identifier)
    {
        return $this->model->where('identifier', $identifier)
            ->latest()
            ->first();
    }
}
