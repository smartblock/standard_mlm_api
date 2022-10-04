<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 10:25 AM
 */

namespace App\Interfaces;

interface WalletDetailInterface extends EloquentRepositoryInterface
{
    /**
     * @param int $user_id
     * @param int $wallet
     * @return mixed
     */
    public function getTotalByUserID(int $user_id, int $wallet);

    /**
     * @param int $user_id
     * @param int $wallet_id
     * @return mixed
     */
    public function getLastTransByUserID(int $user_id, int $wallet_id);
}
