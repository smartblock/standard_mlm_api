<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\ProductCategoryInterface;

use Spatie\TranslationLoader\LanguageLine;

class ProductCategoryService extends BaseService
{
    use ResponseAPI;

    public function __construct(ProductCategoryInterface $interface)
    {
        parent::__construct($interface);
    }

    public function getDetailByParent(string $parent)
    {
        $child = $this->interface->all(['*'], ['parent'], [
            'parent' => $parent
        ]);

        return $this->response(true, 'success', $child);
    }

    public function insertNameTranslation(string $category_code, array $category_name)
    {
        $text = [];
        foreach ($category_name as $key => $value) {
            $text[$value['lang']] = $value['name'];
        }

        $key = "category.{$category_code}";
        $lang = LanguageLine::where('key', $key)
            ->where('group', 'product')
            ->first();

        $status = "";
        if (!$lang) {
            $status = LanguageLine::create([
                'group' => 'product',
                'key' => $key,
                'text' => $text
            ]);
        } else {
            $lang->text = $text;
            $status = $lang->save();
        }

        if ($status) {
            return $this->response(true, 'success');
        }

        return $this->response(false, 'failed');
    }

    /**
     * @param string $parent
     * @param string $category_code
     * @param array $category_name
     * @param string $status
     * @param int $seq_no
     * @return array
     */
    public function store(string $parent, string $category_code, array $category_name, string $status, int $seq_no)
    {
        $parent_category = $this->interface->findBy('category_code', $parent);
        if (!$parent_category) {
            return $this->response(false, 'invalid_parent');
        }

        $code = strtolower($category_code);
        $result = $this->interface->create([
            'parent_id' => $parent_category['id'],
            'category_code' => $code,
            'category_name' => $category_code,
            'status' => $status,
            'seq_no' => $seq_no
        ]);
        if ($result) {
            $this->insertNameTranslation($code, $category_name);
            return $this->response(true, 'record_saved_successfully');
        }

        return $this->response(false, 'failed_to_save');
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $seq_no
     * @return array
     */
    public function update(int $id, string $parent_code, string $category_code, array $category_name, string $status, array $options = [])
    {
        $parent = $this->interface->findBy('category_code', $parent_code);
        if (!$parent) {
            return $this->response(false, 'invalid_parent');
        }

        $result = $this->interface->findBy('id', $id, ['*'], [], true);
        if (!$result) {
            return $this->response(false, 'invalid_record');
        }

        $result['category_code'] = $category_code;
        $result['category_name'] = $category_code;
        $result['seq_no'] = $options['seq_no'] ?? null;
//        $result['parent_id'] = $parent['id'];

        if ($result->save()) {
            $this->insertNameTranslation(strtolower($category_code), $category_name);
            return $this->response(true, 'record_updated_successfully');
        }

        return $this->response(false, 'failed_to_update');
    }

}