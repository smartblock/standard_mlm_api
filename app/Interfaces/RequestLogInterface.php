<?php

namespace App\Interfaces;

interface RequestLogInterface extends EloquentRepositoryInterface
{
    /**
     * @param int $user_id
     * @param string $action
     * @param string $uri
     * @param array $params
     * @return mixed
     */
    public function insertLog(int $user_id, string $action, string $uri, array $params);

    /**
     * @param $response
     * @return mixed
     */
    public function updateResponse($response);
}