<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 27/09/2022
 * Time: 12:41 PM
 */

namespace App\Services;

use App\Interfaces\ProductCategoryInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\ProductPackageItemInterface;
use App\Interfaces\ProductVariantInterface;
use App\Interfaces\SettingInterface;

class ProductMixMatchService extends ProductService
{
    public function __construct(
        ProductInterface $interface,
        ProductCategoryInterface $categoryInterface,
        ProductVariantInterface $variantInterface,
        ProductPackageItemInterface $itemInterface,
        SettingInterface $settingInterface)
    {
        parent::__construct($interface, $categoryInterface, $variantInterface, $itemInterface, $settingInterface);
    }

    public function storeMixMatch(string $category, string $code, array $products, float $price, int $quantity, array $options)
    {
        $category = $this->categoryInterface->findBy('category_code', $category);
        if (!$category) {
            return $this->response(false, 'invalid_category');
        }

        $product = $this->interface->findBy('code', $code);
        if ($product) {
            return $this->response(false, 'code_already_exist');
        }

        $result = $this->interface->create([
            'code' => $code,
            'name' => $options['name'] ?? null,
            'category_id' => $category->id,
            'price' => $price,
            'bv' => $options['bv'] ?? null,
            'seq_no' => $options['seq_no'] ?? 0,
            'status' => $options['status'] ?? 'A',
            'weight' => $options['weight'] ?? 0,
            'date_start' => $options['date_start'] ?? null,
            'date_end' => $options['date_end'] ?? null,
            'is_mix_match' => $quantity,
            'group' => 'MIX_MATCH'
        ]);
        if (!$result) {
            return $this->response(false, 'failed_to_save');
        }

        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $product_detail = $this->interface->findBy('code', $value['code']);
                $this->itemInterface->create([
                    'product_id' => $result['id'],
                    'sub_product_id' => $product_detail['id']
                ]);
            }
        }

        return $this->response(true, 'record_saved_successfully');
    }
}