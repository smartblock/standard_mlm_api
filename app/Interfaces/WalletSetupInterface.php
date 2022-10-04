<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 10:25 AM
 */

namespace App\Interfaces;

interface WalletSetupInterface extends EloquentRepositoryInterface
{
    /**
     * @param int $user_id
     * @return mixed
     */
    public function balances(int $user_id);
}
