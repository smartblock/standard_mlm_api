<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 10:25 AM
 */

namespace App\Interfaces;

interface WalletSummaryInterface extends EloquentRepositoryInterface
{
    /**
     * @param int $user_id
     * @param int $wallet_code
     * @return mixed
     */
    public function getBalanceById(int $user_id, int $wallet_id);
}
