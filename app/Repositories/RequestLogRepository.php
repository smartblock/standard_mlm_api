<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Interfaces\RequestLogInterface;

use App\Models\RequestLog;

class RequestLogRepository extends BaseRepository implements RequestLogInterface
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
    public function __construct(RequestLog $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $user_id
     * @param string $action
     * @param string $uri
     * @param array $params
     * @return mixed|void
     */
    public function insertLog(int $user_id, string $action, string $uri, array $params)
    {
        $this->log = $this->model->create([
            'user_id' => $user_id,
            'url' => $uri,
            'action' => $action,
            'params' => json_encode($params),
            'response' => null
        ]);
    }

    /**
     * @param $response
     * @return mixed|void
     */
    public function updateResponse($response)
    {
        $this->log->response = $response ?? null;
        $this->log->save();
    }
}