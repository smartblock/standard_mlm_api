<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;
use Carbon\Carbon;
use DB;

use App\Interfaces\WalletTransferInterface;

use App\Models\WalletTransfer;

class WalletTransferRepository extends BaseRepository implements WalletTransferInterface
{
    use ResponseAPI;

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(WalletTransfer $model)
    {
        $this->model = $model;
    }
}
