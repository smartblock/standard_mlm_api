<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 20/09/2022
 * Time: 11:00 AM
 */

namespace App\Services;

use App\Interfaces\StockSupplierInterface;
use App\Traits\ResponseAPI;

use App\Interfaces\StockLocationInterface;

class StockService extends BaseService
{
    use ResponseAPI;

    protected $stockSupplierInterface;

    public function __construct(
        StockLocationInterface $interface,
        StockSupplierInterface $stockSupplierInterface
    )
    {
        parent::__construct($interface);
        $this->stockSupplierInterface = $stockSupplierInterface;
    }

    public function getStockLocations()
    {
        $result = $this->interface->all(['*'], [], []);

        return $this->response(true, 'success', $result);
    }
}