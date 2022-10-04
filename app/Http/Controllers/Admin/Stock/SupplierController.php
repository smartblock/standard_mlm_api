<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Stock\SupplierPutRequest;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Stock\SupplierListRequest;
use App\Http\Requests\Admin\Stock\SupplierPostRequest;

use App\Http\Resources\Admin\StockSupplierResource;

use App\Interfaces\RequestLogInterface;

use App\Services\StockSupplierService;

class SupplierController extends Controller
{
    use ResponseAPI;

    protected $supplierService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        StockSupplierService $stockSupplierService
    )
    {
        parent::__construct($requestLogInterface);
        $this->supplierService = $stockSupplierService;
    }

    public function index(SupplierListRequest $request)
    {
        Try {
            $params = [];

            $result = $this->supplierService->all($request->input('page'), ['*'], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ]);

            return $this->responseTable(StockSupplierResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function save(SupplierPostRequest $request)
    {
        $this->insertLog("SUPPLIER_SAVE", $request);

        Try {
            $result = $this->supplierService->store(
                $request->input('code'),
                $request->input('name'),
                $request->input('seq_no'),
                $request->input('status')
            );
            if ($result['status']) {
                return $this->success($result['message'], "", 201);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function edit(string $id)
    {
        Try {
            $id = decrypt($id);
            $result = $this->supplierService->getDetails($id);
            if ($result) {
                $data = StockSupplierResource::collection([$result['data']]);
                return $this->success($result['message'], $data[0]);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function update(string $id, SupplierPutRequest $request)
    {
        $this->insertLog("SUPPLIER_UPDATE", $request);

        Try {
            $id = decrypt($id);
            $result = $this->supplierService->update(
                $id,
                $request->input('code'),
                $request->input('name'),
                $request->input('seq_no'),
                $request->input('status'));

            if ($result['status']) {
                $this->updateLog($result);
                return $this->success($result['message']);
            }

            $this->updateLog($result);
            return $this->error($result['message'], 400);
        } Catch (\Throwable $exception) {
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error($exception->getMessage());
        }
    }

    public function delete(string $id)
    {
        Try {
            $id = decrypt($id);
            $result = $this->supplierService->delete($id);
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message'], 400);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
