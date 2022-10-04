<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\GeneralSetting\SettingListRequest;
use App\Http\Requests\Admin\GeneralSetting\SettingPutRequest;
use App\Http\Requests\Admin\GeneralSetting\SettingPostRequest;

use App\Http\Resources\GeneralSettingResource;

use App\Interfaces\RequestLogInterface;

use App\Services\GeneralSettingService;

class GeneralSettingController extends Controller
{
    use ResponseAPI;

    private $settingService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        GeneralSettingService $settingService
    )
    {
        parent::__construct($requestLogInterface);
        $this->settingService = $settingService;
    }

    public function index(SettingListRequest $request)
    {
        Try {
            $params = [];
            if ($request->filled('type')) {
                $params['type'] = $request->input('type');
            }

            $result = $this->settingService->all($request->input('page'), [
                'id', 'country_id', 'type', 'code', 'name', 'seq_no', 'created_at'
            ], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ]);

            return $this->responseTable(GeneralSettingResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function save(SettingPostRequest $request)
    {
        $this->insertLog('SETTING_SAVE', $request);
        DB::beginTransaction();

        Try {
            $inputs = $request->all();
            $result = $this->settingService->store(
                $inputs['type'],
                $inputs['code'],
                $inputs['name'],
                $inputs['seq_no'] ?? 0
            );

            if ($result['status']) {
                DB::commit();
                $this->updateLog($result);
                return $this->success($result['message']);
            }

            DB::rollback();
            $this->updateLog($result);
            return $this->error($result['message']);
        } Catch (\Throwable $exception) {
            DB::rollback();
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error();
        }
    }

    public function edit(string $type, string $code)
    {
        $result = $this->settingService->getDetailsByCode($type, $code);
        if ($result['status']) {
            $data = GeneralSettingResource::collection([$result['data']]);
            return $this->success($result['message'], $data[0]);
        }

        return $this->error($result['message'], 400);
    }

    public function update(string $id, SettingPutRequest $request)
    {
        $this->insertLog('SETTING_UPDATE', $request);
        DB::beginTransaction();

        Try {
            $id = decrypt($id);
            $inputs = $request->all();
            $result = $this->settingService->update(
                $id,
                $inputs['country_code'],
                $inputs['name'],
                $inputs['status'],
                $inputs['seq_no'] ?? 0
            );

            if ($result['status']) {
                DB::commit();
                $this->updateLog($result);
                return $this->success($result['message']);
            }

            DB::rollback();
            $this->updateLog($result);
            return $this->error($result['message']);
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
