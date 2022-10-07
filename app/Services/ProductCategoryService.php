<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Interfaces\ProductCategoryDetailInterface;
use App\Interfaces\SysLanguageInterface;
use App\Traits\ResponseAPI;

use App\Interfaces\ProductCategoryInterface;

use Spatie\TranslationLoader\LanguageLine;

class ProductCategoryService extends BaseService
{
    use ResponseAPI;

    public $defaultLanguage;
    protected $detailInterface, $langInterface;


    public function __construct(
        ProductCategoryInterface $interface,
        ProductCategoryDetailInterface $detailInterface,
        SysLanguageInterface $langInterface
    )
    {
        parent::__construct($interface);
        $this->langInterface = $langInterface;
        $this->detailInterface = $detailInterface;
        $this->defaultLanguage = $this->langInterface->getDefaultLanguage()['code'];
    }

    /**
     * @param array $category_name
     * @return string
     */
    public function getDefaultName(array $category_name)
    {
        $name = "";
        foreach ($category_name as $key => $value) {
            if ($this->defaultLanguage == $value['lang']) {
                $name = $value['name'];
            }
        }

        return $name;
    }

    public function listAll(array $columns = ['*'], array $relations = [], array $params = [], array $orders = [], string $lock = null)
    {
        return $this->interface->all($columns, $relations, $params, $orders, $lock);
    }

    /**
     * @param string $parent
     * @return array
     */
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
     * @param array $category_name
     * @param string $status
     * @param int $seq_no
     * @return array
     */
    public function store(string $parent, array $category_name, string $status, int $seq_no)
    {
        $parent_category = $this->interface->findBy('category_code', $parent);
        if (!$parent_category) {
            return $this->response(false, 'invalid_parent');
        }

        $name = $this->getDefaultName($category_name);

        $code = removeSpecialCharacters(strtolower($name));
        $result = $this->interface->create([
            'parent_id' => $parent_category['id'],
            'category_code' => $code,
            'category_name' => $name,
            'status' => $status,
            'seq_no' => $seq_no
        ]);
        if ($result) {
            foreach ($category_name as $key => $value) {
                $lang = $this->langInterface->findBy('code', $value['lang']);
                $this->detailInterface->create([
                    'category_id' => $result['id'],
                    'language_id' => $lang['id'],
                    'name' => $value['name']
                ]);
            }

            return $this->response(true, 'record_saved_successfully');
        }

        return $this->response(false, 'failed_to_save');
    }

    /**
     * @param int $id
     * @param string $category_code
     * @param array $category_name
     * @param string $status
     * @param array $options
     * @return array
     */
    public function update(int $id, array $category_name, string $status, array $options = [])
    {
        $result = $this->interface->findBy('id', $id, ['*'], [], true);
        if (!$result) {
            return $this->response(false, 'invalid_record');
        }

        $category = $this->interface->find($id);
        $name = $this->getDefaultName($category_name);

        $code = removeSpecialCharacters(strtolower($name));
        $category['category_code'] = $code;
        $category['category_name'] = $name;
        $category['seq_no'] = $options['seq_no'] ?? null;
        $category['status'] = $status;

        if ($category->save()) {
            foreach ($category_name as $key => $value) {
                $lang = $this->langInterface->findBy('code', $value['lang']);
                $this->detailInterface->updateByLanguageID($id, $lang['id'], [
                    'name' => $value['name']
                ]);
            }

            return $this->response(true, 'record_updated_successfully');
        }

        return $this->response(false, 'failed_to_update');
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        $result = $this->interface->deleteById($id);
        if ($result) {
            return $this->response(true, 'record_deleted_successfully');
        }

        return $this->response(false, 'failed_to_delete');
    }
}