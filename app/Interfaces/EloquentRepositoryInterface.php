<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 05/11/2021
 * Time: 2:45 PM
 */

namespace App\Interfaces;

interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param int $model_id
     * @param array $attributes
     * @return mixed
     */
    public function update(int $model_id, array $attributes);

    /**
     * @param string $field
     * @param $value
     * @param array $attributes
     * @return mixed
     */
    public function updateBy(string $field, $value, array $attributes);

    /**
     * @param int $model_id
     * @return mixed
     */
    public function deleteById(int $model_id);

    /**
     * @param int $id
     * @param string $lock
     * @return mixed
     */
    public function find(int $id, string $lock);

    /**
     * @param string $field
     * @param string $value
     * @param array $columns
     * @param array $relations
     * @param string $lock
     * @return mixed
     */
    public function findBy(string $field, string $value, array $columns = ['*'], array $relations = [], string $lock = null);

    /**
     * @param array $columns
     * @param array $relations
     * @param array $params
     * @param array $orders
     * @param string|null $lock
     * @return mixed
     */
    public function all(array $columns = ['*'], array $relations = [], array $params = [], array $orders = [], string $lock = null);

    /**
     * @param int $start
     * @param int $length
     * @param array $columns
     * @param array $params
     * @param array $orders
     * @param array $relations
     * @param null $group
     * @param string $lock
     * @return mixed
     */
    public function pagination(int $start, int $length, array $columns = [], array $params = [], array $orders = [], array $relations = [], $group = null, string $lock);

    /**
     * @param $relations
     * @return mixed
     */
    public function with($relations);

    /**
     * @return mixed
     */
    public function perPage();

    /**
     * @param int $length
     * @return mixed
     */
    public function setPerPage(int $length);
}
