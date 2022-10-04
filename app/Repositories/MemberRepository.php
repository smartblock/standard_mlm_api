<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Interfaces\MemberInterface;

use App\Models\User;

class MemberRepository extends BaseRepository implements MemberInterface
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
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public static function factory()
    {
        return self::factory();
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
            if (isset($params['role'])) {
                $query->role($params['role']);
            }

            if (isset($params['username'])) {
                $query->where('username', 'LIKE', "%{$params['username']}%");
            }

            if (isset($params['name'])) {
                $query->where('name', 'LIKE', "%{$params['name']}%");
            }

            if (isset($params['email'])) {
                $query->where('email', 'LIKE', "{$params['email']}%");
            }

            if (isset($params['mobile_no'])) {
                $query->where('mobile_no', 'LIKE', "{$params['mobile_no']}%");
            }
        }

        $query->with($relations);
        $total_records = $query->count();
        $order_by = !empty($orders) ? $orders['column'] : 'id';
        $order_dir = !empty($orders) ? $orders['dir'] : 'desc';

        if ($lock) {
            $query->lockForUpdate();
        }

        if ($group_column) {
            $query->groupBy($group_column);
        }

        $query->skip($start)->take($length)->orderBy($order_by, $order_dir)->select($columns);

        return [
            'total' => $total_records,
            'data' => $query->get()
        ];
    }

    /**
     * @param int $length
     * @return mixed|void
     */
    public function setPerPage(int $length)
    {
        $this->model->setPerPage($length);
    }
}
