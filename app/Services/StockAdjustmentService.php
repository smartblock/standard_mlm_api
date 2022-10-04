<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Interfaces\ProductInterface;
use App\Interfaces\StockGoodReceiveInterface;
use App\Interfaces\StockLocationInterface;
use App\Interfaces\StockSupplierInterface;
use App\Traits\ResponseAPI;

use App\Interfaces\StockGoodReceiveItemInterface;

class StockAdjustmentService extends GoodReceiveService
{
    use ResponseAPI;

    public function __construct(
        StockGoodReceiveInterface $interface,
        StockGoodReceiveItemInterface $itemInterface,
        StockSupplierInterface $supplierInterface,
        StockLocationInterface $stockInterface,
        ProductInterface $productInterface,
        SysDocService $docService)
    {
        parent::__construct($interface, $itemInterface, $supplierInterface, $stockInterface, $productInterface, $docService);
    }

    public function store(array $product, string $doc_date, string $trans_type, string $status, array $options = [])
    {
        $supplier = "";
        if (isset($options['supplier_code'])) {
            $supplier = $this->supplierInterface->findBy('code', $options['supplier_code']);
            if (!$supplier) {
                return $this->response(false, 'invalid_supplier');
            }
        }

        $stock_result = $this->interface->create([
            'doc_no' => '',
            'doc_date' => $doc_date,
            'trans_type' => $trans_type,
            'supplier_id' => $supplier['id'] ?? null,
            'status' => $status,
            'reason' => $options['reason'] ?? null,
            'remark' => $options['remark'] ?? null
        ]);
        if (!$stock_result) {
            return $this->response(false, 'failed_to_save_stock_record');
        }

        $failed_arry = [];
        foreach ($product as $key => $value) {
            $product = $this->productInterface->findBy('code', $value['code']);
            if (!$product) {
                return $this->response(false, 'invalid_product');
            }

            $item_result = $this->itemInterface->create([
                'stock_receive_id' => $stock_result['id'],
                'product_id' => $product['id'],
                'total_in' => ($value['qty'] > 0) ? $value['qty'] : 0,
                'total_out' => ($value['qty'] < 0) ? abs($value['qty']) : 0,
                'balance' => abs($value['qty'])
            ]);
            if (!$item_result) {
                $failed_arry[] = $value['code'];
            }
        }
        if (empty($failed_arry)) {
            return $this->response(true, 'record_saved_successfully');
        }

        return $this->response(false, 'failed_to_save');
    }
}