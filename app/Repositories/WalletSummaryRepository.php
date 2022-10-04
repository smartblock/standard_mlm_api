<?php

namespace App\Repositories;

use App\Interfaces\WalletSummaryInterface;

use App\Models\WalletSummary;

class WalletSummaryRepository extends BaseRepository implements WalletSummaryInterface
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
    public function __construct(WalletSummary $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $user_id
     * @param int $wallet_id
     * @param null $lock
     * @return mixed
     */
    public function getBalanceById(int $user_id, int $wallet_id, $lock = null)
    {
        $query = $this->model
            ->where('user_id', $user_id)
            ->where('wallet_id', $wallet_id);

        if ($lock) {
            $query->lockForUpdate();
        }

        return $query->first();
    }
}
