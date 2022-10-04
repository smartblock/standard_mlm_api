<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\SysLanguage\SysLanguageListRequest;
use App\Http\Requests\Admin\SysLanguage\SysLanguageAllRequest;
use App\Http\Requests\Admin\SysLanguage\SysLanguagePostRequest;
use App\Http\Requests\Admin\SysLanguage\SysLanguagePutRequest;

use App\Http\Resources\Admin\SysLanguageResource;

use App\Interfaces\RequestLogInterface;

use App\Services\SysLanguageService;

class SysLanguageController extends Controller
{
    use ResponseAPI;

    protected $languageService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        SysLanguageService $languageService
    ) {
        parent::__construct($requestLogInterface);
        $this->languageService = $languageService;
    }

    public function index(SysLanguageListRequest $request)
    {
        Try {
            $result = $this->languageService->all($request->input('page'), ['*'], $request->all(), [
                'column' => 'seq_no',
                'dir' => 'asc'
            ], [
                'createdBy', 'updatedBy'
            ]);

            return $this->responseTable(SysLanguageResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function all(SysLanguageAllRequest $request)
    {
        Try {
            $result = $this->languageService->listAll(['*'], [], $request->all(), [
                'column' => 'name',
                'dir' => 'asc'
            ]);

            return $this->success('success', SysLanguageResource::collection($result));
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function save(SysLanguagePostRequest $request)
    {
        $this->insertLog('SYS_LANGUAGE_SAVE', $request);
        DB::beginTransaction();

        Try {
            $inputs = $request->all();
            $result = $this->languageService->store($inputs['locale'], $inputs['code'], $inputs['name'], $inputs['seq_no'] ?? 0);

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

    public function edit(string $id)
    {
        Try {
            $id = decrypt($id);
            $result = $this->languageService->getDetails($id);
            if ($result['status']) {
                $data = SysLanguageResource::collection([$result['data']]);
                return $this->success($result['message'], $data[0]);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function update(string $id, SysLanguagePutRequest $request)
    {
        $this->insertLog('SYS_LANGUAGE_UPDATE', $request);
        DB::beginTransaction();

        Try {
            $id = decrypt($id);
            $inputs = $request->all();
            $result = $this->languageService->update(
                $id,
                $inputs['locale'],
                $inputs['code'],
                $inputs['name'],
                $inputs['seq_no'] ?? 0);

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

    public function delete(string $id)
    {
        try {
            $id = decrypt($id);
            $result = $this->languageService->delete($id);
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message']);
        } catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
