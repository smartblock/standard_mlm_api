<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\StockLocationInterface;
use App\Interfaces\StockSupplierInterface;

class StockSupplierService extends StockService
{
    use ResponseAPI;

    public function __construct(StockLocationInterface $interface, StockSupplierInterface $stockSupplierInterface)
    {
        parent::__construct($interface, $stockSupplierInterface);
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!empty($params['limit'])) {
            $this->stockSupplierInterface->setPerPage($params['limit']);
        }

        $limit = $this->interface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->stockSupplierInterface->pagination($start, $limit, $columns, $params, $order, $relations);

        return $this->responsePaginate($limit, $result);
    }

    public function store(string $code, string $name, int $seq_no, string $status)
    {
        $supplier = $this->stockSupplierInterface->findBy('code', $code);
        if ($supplier) {
            return $this->response(false, 'data_already_exist');
        }

        $result = $this->stockSupplierInterface->create([
            'code' => $code,
            'name' => $name,
            'seq_no' => $seq_no,
            'status' => $status
        ]);
        if ($result) {
            return $this->response(true, 'record_saved_successfully');
        }

        return $this->response(false, 'failed_to_save');
    }

    public function update(int $id, string $code, string $name, int $seq_no, string $status)
    {
        $supplier = $this->stockSupplierInterface->find($id);
        if (!$supplier) {
            return $this->response(false, 'invalid_supplier');
        }

        $supplier['code'] = $code;
        $supplier['name'] = $name;
        $supplier['seq_no'] = $seq_no;
        $supplier['status'] = $status;
        $result = $supplier->save();
        if ($result) {
            return $this->response(true, 'record_saved_successfully');
        }

        return $this->response(false, 'failed_to_save');
    }
}