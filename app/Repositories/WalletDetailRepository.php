<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;
use Carbon\Carbon;
use DB;

use App\Interfaces\WalletDetailInterface;

use App\Models\WalletDetail;

class WalletDetailRepository extends BaseRepository implements WalletDetailInterface
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
    public function __construct(WalletDetail $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $start
     * @param int $length
     * @param array $columns
     * @param array $params
     * @param array $orders
     * @param array $relations
     * @param null $group_column
     * @param string|null $lock
     * @return array|mixed
     */
    public function pagination(int $start, int $length, array $columns = [], array $params = [], array $orders = [], array $relations = [], $group_column = null, string $lock = null)
    {
        $query = $this->model->with($relations);
        if (!empty($params)) {
            if ($params['username']) {
                $username = $params['username'];
                $query->whereHas('user', function ($q) use ($username){
                    $q->where('username', $username);
                });
            }

            if ($params['date_from']) {
                $query->where('created_at', '>=', $params['date_from']);
            }
        }

        $total_records = $query->count();
        $order_by = !empty($orders) ? $orders['column'] : 'id';
        $order_dir = !empty($orders) ? $orders['dir'] : 'desc';

        if ($lock) {
            $query->lockForUpdate();
        }

        if ($group_column) {
            $query->groupBy($group_column);
        }

        $data = $query->skip($start)->take($length)->orderBy($order_by, $order_dir)->select($columns);

        return [
            'total' => $total_records,
            'data' => $data->get()
        ];
    }

    /**
     * @param int $user_id
     * @param int $wallet_id
     * @param string $trans_type
     * @param float $total_in
     * @param float $total_out
     * @param array $params
     * @return mixed
     */
    public function store(int $user_id, int $wallet_id, string $trans_type, float $total_in, float $total_out, array $params)
    {
        $last_balance = 0;
        $last_trans = $this->getLastTransByUserID($user_id, $wallet_id);
        if ($last_trans) {
            $last_balance = $last_trans['balance'];
        }

        $wallet = new $this->model;
        $wallet->trans_date = Carbon::now()->format('Y-m-d');
        $wallet->user_id = $user_id;
        $wallet->wallet_id = $wallet_id;
        $wallet->trans_type = strtoupper($trans_type);
        $wallet->total_in = $total_in;
        $wallet->total_out = $total_out;
        $wallet->balance = $last_balance + $total_in - $total_out;
        $wallet->remark = $params['remark'] ?? null;

        if (isset($params['transfer_id'])) {
            $wallet->transfer_id = $params['transfer_id'];
        }

        if (isset($params['withdraw_id'])) {
            $wallet->withdraw_id = $params['withdraw_id'];
        }

        if (isset($params['sales_id'])) {
            $wallet->sales_id = $params['sales_id'];
        }

        if ($wallet->save()) {
            return $this->response(true, 'saved_successfully');
        }

        return $this->response(false, 'saved_successfully');
    }

    /**
     * @param int $user_id
     * @param int $wallet_id
     * @return mixed
     */
    public function getTotalByUserID(int $user_id, int $wallet_id)
    {
        return $this->model->where('user_id', $user_id)
            ->where('wallet_id', $wallet_id)
            ->groupBy('user_id')
            ->first([
                'user_id',
                DB::raw('sum(total_in) total_in'),
                DB::raw('sum(total_out) total_out'),
            ]);
    }

    public function getLastTransByUserID(int $user_id, int $wallet_id)
    {
        return $this->model->where('user_id', $user_id)
            ->where('wallet_id', $wallet_id)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
