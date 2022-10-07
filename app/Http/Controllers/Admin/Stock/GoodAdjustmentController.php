<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Stock\GoodAdjustmentPostRequest;
use App\Interfaces\RequestLogInterface;
use App\Services\StockAdjustmentService;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class GoodAdjustmentController extends Controller
{
    use ResponseAPI;

    private $adjustmentService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        StockAdjustmentService $adjustmentService
    )
    {
        parent::__construct($requestLogInterface);
        $this->adjustmentService = $adjustmentService;
    }

    public function index()
    {

    }

    public function save(GoodAdjustmentPostRequest $request)
    {
        $this->insertLog("STOCK_ADJUSTMENT_SAVE", $request);

        Try {
            $params = [];
            if ($request->filled('ref_no')) {
                $params['ref_no'] = $request->input('ref_no');
            }

            if ($request->filled('reason')) {
                $params['reason'] = $request->input('reason');
            }

            if ($request->filled('stock_code')) {
                $params['stock_code'] = $request->input('stock_code');
            }

            $result = $this->adjustmentService->store(
                json_decode($request->input('product'), true),
                $request->input('date'),
                'GOOD_ADJUSTMENT',
                'APPROVED',
                $params
            );
            if ($result['status']) {
                return $this->success($result['message'], "", 201);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }
}
