<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Interfaces\StockGoodReceiveItemInterface;
use App\Traits\ResponseAPI;

use App\Interfaces\StockGoodReceiveInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\StockSupplierInterface;
use App\Interfaces\StockLocationInterface;

class GoodReceiveService extends BaseService
{
    use ResponseAPI;

    protected $itemInterface, $supplierInterface, $productInterface, $stockInterface;
    protected $docType, $docStatus, $docDate;
    protected $docService;

    public function __construct(
        StockGoodReceiveInterface $interface,
        StockGoodReceiveItemInterface $itemInterface,
        StockSupplierInterface $supplierInterface,
        StockLocationInterface $stockInterface,
        ProductInterface $productInterface,
        SysDocService $docService
    )
    {
        parent::__construct($interface);
        $this->itemInterface = $itemInterface;
        $this->supplierInterface = $supplierInterface;
        $this->productInterface = $productInterface;
        $this->stockInterface = $stockInterface;
        $this->docStatus = "APPROVED";
        $this->docType = "GR";
        $this->docService = $docService;
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        return parent::all($page, $columns, $params, $order, $relations); // TODO: Change the autogenerated stub
    }

    public function setDocStatus(string $status)
    {
        $this->docStatus = strtoupper($status);
    }

    public function store(array $product, string $doc_date, string $trans_type, string $status, array $options = [])
    {
        $supplier = "";
        if ($options['supplier_code']) {
            $supplier = $this->supplierInterface->findBy('code', $options['supplier_code']);
            if (!$supplier) {
                return $this->response(false, 'invalid_supplier');
            }
        }

        $location = "";
        if ($options['stock_code']) {
            $location = $this->stockInterface->findBy('stock_code', $options['stock_code']);
            if (!$location) {
                return $this->response(false, 'invalid_stock');
            }
        }

        $doc_no = $this->docService->getRunningNo("GR");

        $stock_result = $this->interface->create([
            'doc_no' => '',
            'doc_date' => $doc_date,
            'trans_type' => $trans_type,
            'supplier_id' => $supplier['id'] ?? null,
            'stock_id' => $location['id'] ?? null,
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
            $item_result = $this->itemInterface->create([
                'stock_receive_id' => $stock_result['id'],
                'product_id' => $product['id'],
                'total_in' => $value['qty'],
                'total_out' => 0,
                'balance' => $value['qty']
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