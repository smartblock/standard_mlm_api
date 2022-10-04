<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\StockSupplierInterface;

class StockSupplierService extends BaseService
{
    use ResponseAPI;

    public function __construct(StockSupplierInterface $interface)
    {
        parent::__construct($interface);
    }

    public function store(string $code, string $name, int $seq_no, string $status)
    {
        $supplier = $this->interface->findBy('code', $code);
        if ($supplier) {
            return $this->response(false, 'data_already_exist');
        }

        $result = $this->interface->create([
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
        $supplier = $this->interface->find($id);
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