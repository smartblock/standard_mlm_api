<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Interfaces\PasswordResetInterface;

use App\Models\PasswordReset;

class PasswordResetRepository extends BaseRepository implements PasswordResetInterface
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
    public function __construct(PasswordReset $model)
    {
        $this->model = $model;
    }
}
