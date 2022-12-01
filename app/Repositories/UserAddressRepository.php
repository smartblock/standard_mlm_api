<?php

namespace App\Repositories;

use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Model;

use App\Interfaces\UserAddressInterface;

class UserAddressRepository extends BaseRepository implements UserAddressInterface
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
    public function __construct(UserAddress $model)
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
            foreach($params as $x => $val) {
                $query->where($x, $val);
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
     * @param string $address_type
     * @return mixed
     */
    public function removeShippingStatus(int $user_id, string $address_type)
    {
        $query = $this->model->where('user_id', $user_id);

        if ($address_type == 'SHIPPING') {
            return $query->where('is_default_shipping', 1)
                ->update([
                    'is_default_shipping' => 0
                ]);
        } else {
            return $query->where('is_default_billing', 1)
                ->update([
                    'is_default_billing' => 0
                ]);
        }
    }
}