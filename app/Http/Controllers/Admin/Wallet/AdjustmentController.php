<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Wallet\AdjustmentPostRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\WalletAdjustmentService;

class AdjustmentController extends Controller
{
    use ResponseAPI;

    private $adjustmentService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        WalletAdjustmentService $adjustmentService
    )
    {
        parent::__construct($requestLogInterface);
        $this->adjustmentService = $adjustmentService;
    }

    public function save(AdjustmentPostRequest $request)
    {
        $this->insertLog("ADJUSTMENT_POST", $request);
        DB::beginTransaction();

        Try {
            $inputs = $request->all();
            $params = [];
            if ($request->filled('remark')) {
                $params['remark'] = $inputs['remark'];
            }

            $result = $this->adjustmentService->store($inputs['username'], $inputs['wallet_type'], $inputs['amount'], $params);
            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            DB::rollback();
            return $this->error();
        }
    }
}
