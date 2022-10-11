<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\StockLocationResource;
use App\Interfaces\RequestLogInterface;

use App\Services\StockService;
use App\Traits\ResponseAPI;

class StockLocationController extends Controller
{
    use ResponseAPI;

    protected $stockService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        StockService $stockService
    )
    {
        parent::__construct($requestLogInterface);
        $this->stockService = $stockService;
    }

    public function all()
    {
        $result = $this->stockService->getStockLocations();

        return $this->success($result['message'], StockLocationResource::collection($result['data']));
    }
}
