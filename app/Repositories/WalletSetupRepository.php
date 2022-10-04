<?php

namespace App\Repositories;

use DB;

use App\Interfaces\WalletSetupInterface;

use App\Models\WalletSetup;

class WalletSetupRepository extends BaseRepository implements WalletSetupInterface
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
    public function __construct(WalletSetup $model)
    {
        $this->model = $model;
    }

    public function balances(int $user_id)
    {
        $query = $this->model->leftJoin('wallet_summaries', function($q) use ($user_id){
            $q->on('wallet_summaries.wallet_id', 'wallet_setups.id')
                ->on('wallet_summaries.user_id', DB::raw("{$user_id}"));
        })
            ->leftJoin('users', 'users.id', 'wallet_summaries.user_id');

        return $query->orderBy('seq_no')->get([
            'wallet_setups.*',
            DB::raw('ifnull(wallet_summaries.balance, 0) balance'),
            DB::raw('ifnull(wallet_summaries.total_in, 0) total_debit'),
            DB::raw('ifnull(wallet_summaries.total_out, 0) total_credit')
        ]);
    }
}
