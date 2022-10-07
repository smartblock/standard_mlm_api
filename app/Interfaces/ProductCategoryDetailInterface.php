<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 10:25 AM
 */

namespace App\Interfaces;

interface ProductCategoryDetailInterface extends EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function updateOrCreate(array $attributes);

    /**
     * @param int $id
     * @param int $lang
     * @param array $attributes
     * @return mixed
     */
    public function updateByLanguageID(int $id, int $lang, array $attributes);
}
