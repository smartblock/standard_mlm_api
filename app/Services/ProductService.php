<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 22/09/2022
 * Time: 3:24 PM
 */

namespace App\Services;

use App\Interfaces\ProductCategoryInterface;
use App\Interfaces\ProductPackageItemInterface;
use App\Interfaces\ProductVariantInterface;
use App\Interfaces\SettingInterface;
use App\Traits\ResponseAPI;

use App\Interfaces\ProductInterface;

class ProductService extends BaseService
{
    use ResponseAPI;

    protected $categoryInterface, $variantInterface, $settingInterface, $itemInterface;

    public function __construct(
        ProductInterface $interface,
        ProductCategoryInterface $categoryInterface,
        ProductVariantInterface $variantInterface,
        ProductPackageItemInterface $itemInterface,
        SettingInterface $settingInterface
    )
    {
        parent::__construct($interface);
        $this->categoryInterface = $categoryInterface;
        $this->variantInterface = $variantInterface;
        $this->settingInterface = $settingInterface;
        $this->itemInterface = $itemInterface;
    }

    public function store(string $category, string $code, float $price, string $status, array $options)
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
            'name' => $code ?? null,
            'category_id' => $category->id,
            'price' => $price,
            'bv' => $options['bv'] ?? null,
            'seq_no' => $options['seq_no'] ?? 0,
            'status' => $status,
            'weight' => $options['weight'] ?? null
        ]);
        if (!$result) {
            return $this->response(false, 'failed_to_save');
        }

        $variant = $options['variant'];
        if (!empty($variant)) {
            foreach ($variant as $key => $value) {
                $variant_type = $this->settingInterface->findBy('code', $value['key']);
                $this->variantInterface->create([
                    'product_id' => $result['id'],
                    'variant_id' => $variant_type['id'],
                    'variant_text' => $value['value']
                ]);
            }
        }

        return $this->response(true, 'record_saved_successfully');
    }

    public function storeVariant(int $product_id, array $variant)
    {
        $failed_arry = [];
        foreach ($variant as $key => $value) {
            $variant_type = $this->settingInterface->findBy('code', $value['key']);
            $result = $this->variantInterface->create([
                'product_id' => $product_id,
                'variant_id' => $variant_type['id'],
                'variant_text' => $value['value']
            ]);

            if (!$result) {
                $failed_arry[] = $value;
            }
        }

        if (empty($failed_arry)) {
            return $this->response(true, 'success');
        }

        return $this->response(false, 'failed');
    }

    public function savePackageItem(array $products, string $code, string $category_code, float $price, array $options = [])
    {
        $category = $this->categoryInterface->findBy('category_code', $category_code);
        if (!$category) {
            return $this->response(false, 'invalid_category');
        }

        $product = $this->interface->findBy('code', $code);
        if ($product) {
            return $this->response(false, 'code_already_exist');
        }

        $result = $this->interface->create([
            'code' => $code,
            'name' => $code ?? null,
            'category_id' => $category->id,
            'price' => $price,
            'bv' => $options['bv'] ?? null,
            'seq_no' => $options['seq_no'] ?? 0,
            'status' => $options['status'] ?? "I",
            'weight' => $options['weight'] ?? null,
            'group' => 'PACKAGE'
        ]);

        if (!$result) {
            return $this->response(false, 'failed_to_save');
        }

        foreach ($products as $key => $value) {
            $item = $this->interface->findBy('code', $value['code']);
            $this->itemInterface->create([
                'product_id' => $result['id'],
                'sub_product_id' => $item['id'],
                'quantity' => $value['qty']
            ]);
        }

        return $this->response(true, 'record_save_successfully');
    }
}