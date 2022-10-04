<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Interfaces\OneTimePasswordInterface;

use App\Models\OneTimePassword;

class OneTimePasswordRepository extends BaseRepository implements OneTimePasswordInterface
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
    public function __construct(OneTimePassword $model)
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
