<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\TransferResource;
use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Wallet\TransferListRequest;
use App\Http\Requests\Admin\Wallet\TransferPostRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\WalletTransferService;

class TransferController extends Controller
{
    use ResponseAPI;

    private $transferService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        WalletTransferService $transferService
    )
    {
        parent::__construct($requestLogInterface);
        $this->transferService = $transferService;
    }

    public function index(TransferListRequest $request)
    {
        Try {
            $params = [];
            $inputs = $request->all();
            if ($request->filled('sender')) {
                $params['sender'] = $inputs['sender'];
            }

            if ($request->filled('date_from')) {
                $params['date_from'] = $inputs['date_from'];
            }

            if ($request->filled('date_to')) {
                $params['date_to'] = $inputs['date_to'];
            }

            if ($request->filled('wallet_type')) {
                $params['wallet_type'] = $inputs['wallet_type'];
            }

            $result = $this->transferService->all($request->input('page'), ['*'], $params, [
                'column' => 'created_at',
                'dir' => 'desc'
            ], [
                'wallet', 'sender', 'receiver'
            ]);

            return $this->responseTable(TransferResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function save(TransferPostRequest $request)
    {
        $this->insertLog("TRANSFER_POST", $request);
        DB::beginTransaction();

        Try {
            $inputs = $request->all();
            $params = [];
            if ($request->filled('remark')) {
                $params['remark'] = $inputs['remark'];
            }

            $result = $this->transferService->store(
                $inputs['username_from'],
                $inputs['username_to'],
                $inputs['wallet_from'],
                $inputs['wallet_to'],
                $inputs['amount'], $params);
            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            DB::rollback();
            return $this->error($exception->getMessage());
        }
    }
}
