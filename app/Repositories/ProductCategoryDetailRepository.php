<?php

namespace App\Repositories;

use App\Traits\ResponseAPI;
use DB;

use App\Interfaces\ProductCategoryDetailInterface;

use App\Models\ProductCategoryDetail;

class ProductCategoryDetailRepository extends BaseRepository implements ProductCategoryDetailInterface
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
    public function __construct(ProductCategoryDetail $model)
    {
        $this->model = $model;
    }

    public function updateOrCreate(array $attributes)
    {
        return $this->model->updateOrCreate($attributes);
    }

    /**
     * @param int $id
     * @param int $lang
     * @param array $attributes
     * @return mixed
     */
    public function updateByLanguageID(int $id, int $lang, array $attributes)
    {
         $data = $this->model->where('category_id', $id)
             ->where('language_id', $lang)
             ->first();

         if ($data) {
             $data->update($attributes);
         } else {
             $data['category_id'] = $id;
             $data['language_id'] = $lang;
             $data['name'] = $attributes['name'];
             self::create($data);
         }
    }
}
