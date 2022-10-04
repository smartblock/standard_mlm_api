<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Stock\GoodReceivePostRequest;
use App\Http\Requests\Admin\Stock\GoodReceiveListRequest;

use App\Interfaces\RequestLogInterface;

use App\Http\Resources\Admin\StockGoodReceiveResource;

use App\Services\GoodReceiveService;

class GoodReceiveController extends Controller
{
    use ResponseAPI;

    private $goodReceiveService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        GoodReceiveService $goodReceiveService
    )
    {
        parent::__construct($requestLogInterface);
        $this->goodReceiveService = $goodReceiveService;
    }

    public function index(GoodReceiveListRequest $request)
    {
        Try {
            $params = [];
            $result = $this->goodReceiveService->all($request->input('page'), ['*'], $params, [
                'column' => 'created_at',
                'dir' => 'desc'
            ], ['stock']);

            return $this->responseTable(StockGoodReceiveResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function stockBalance( $request)
    {

    }

    public function save(GoodReceivePostRequest $request)
    {
        $this->insertLog("GOOD_RECEIVE_SAVE", $request);
        DB::beginTransaction();

        Try {
            $params = [];
            if ($request->filled('ref_no')) {
                $params['ref_no'] = $request->input('ref_no');
            }

            if ($request->filled('remark')) {
                $params['remark'] = $request->input('remark');
            }

            if ($request->filled('supplier_code')) {
                $params['supplier_code'] = $request->input('supplier_code');
            }

            if ($request->filled('stock_code')) {
                $params['stock_code'] = $request->input('stock_code');
            }

            $result = $this->goodReceiveService->store(
                json_decode($request->input('product'), true),
                $request->input('date'),
                'GOOD_RECEIVE',
                'APPROVED',
                $params
            );
            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            DB::rollback();
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error($exception);
        }
    }
}
