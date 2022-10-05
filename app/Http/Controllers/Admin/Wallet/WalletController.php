<?php

namespace App\Http\Controllers\Admin\Wallet;

use App\Http\Controllers\Controller;

use App\Http\Resources\Admin\BalanceResource;
use App\Http\Resources\Admin\WalletResource;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Wallet\StatementListRequest;
use App\Http\Requests\Admin\Wallet\BalanceListRequest;

use App\Http\Resources\Admin\StatementResource;

use App\Interfaces\RequestLogInterface;

use App\Services\WalletService;

class WalletController extends Controller
{
    use ResponseAPI;

    private $walletService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        WalletService $walletService
    )
    {
        parent::__construct($requestLogInterface);
        $this->walletService = $walletService;
    }

    public function index(StatementListRequest $request)
    {
        Try {
            $inputs = $request->all();
            $params['username'] = $inputs['username'];

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

            if ($request->filled('trans_type')) {
                $params['trans_type'] = $inputs['trans_type'];
            }

            $result = $this->walletService->all($request->input('page'), ['*'], $params, [
                'column' => 'created_at',
                'dir' => 'desc'
            ], [
                'wallet'
            ]);

            return $this->responseTable(StatementResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function getBalances(BalanceListRequest $request)
    {
        Try {
            $result = $this->walletService->getBalances($request->input('username'));

            return $this->success('success', BalanceResource::collection($result['data']));
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function all()
    {
        $result = $this->walletService->listAll(['*'], [], [
            'is_allowed_admin' => 1
        ]);

        return $this->success('success', WalletResource::collection($result));
    }
}
