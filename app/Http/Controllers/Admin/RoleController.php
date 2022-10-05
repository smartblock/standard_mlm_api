<?php

namespace App\Http\Controllers\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use DB;
use App\Traits\ResponseAPI;

use App\Http\Controllers\Controller;

use App\Interfaces\RequestLogInterface;

use App\Http\Requests\Admin\Role\RoleListRequest;
use App\Http\Requests\Admin\Role\RoleAllRequest;
use App\Http\Requests\Admin\Role\RolePostRequest;
use App\Http\Requests\Admin\Role\RolePutRequest;

use App\Http\Resources\Admin\RoleResource;

use App\Services\RoleService;

class RoleController extends Controller
{
    use ResponseAPI;

    private $roleService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        RoleService $roleService
    )
    {
        parent::__construct($requestLogInterface);
        $this->roleService = $roleService;
    }

    public function index(RoleListRequest $request): JsonResponse
    {
        Try {
            $params = [];
            if ($request->filled('name')) {
                $params['name'] = $request->input('name');
            }

            if ($request->filled('parent_id')) {
                $params['parent_id'] = decrypt($request->input('parent_id'));;
            }

            if ($request->filled('status')) {
                $params['status'] = $request->input('status');
            }

            if ($request->filled('guard_name')) {
                $params['guard_name'] = $request->input('guard_name');
            }

            $result = $this->roleService->all($request->input('page'), ['*'], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ], ['children']);

            return $this->responseTable(RoleResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function all(RoleAllRequest $request)
    {
        Try {
            $params = [];

            if ($request->filled('parent_id')) {
                $params['parent_id'] = decrypt($request->input('parent_id'));
            }

            if ($request->filled('status')) {
                $params['status'] = $request->input('status');
            }

            if ($request->filled('guard_name')) {
                $params['guard_name'] = $request->input('guard_name');
            }

            $result = $this->roleService->all(1, ['*'], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ], ['children']);

            return $this->success('success', RoleResource::collection($result['data']));
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function tree(RoleAllRequest $request)
    {
        $this->insertLog("ROLE_TREE", $request);

        Try {
            $params = [];

            if ($request->filled('code')) {
                $params['parent_code'] = $request->input('code');
            }

            $result = $this->roleService->tree(['*'], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ], ['children', 'parent']);

            return $this->success('success', RoleTreeResource::collection($result));
        } Catch (\Throwable $exception) {
            $this->updateLog([
                $exception->getMessage(),
                $exception->getLine(),
                $exception->getFile()
            ]);
            return $this->error();
        }
    }

    public function save(RolePostRequest $request): JsonResponse
    {
        $this->insertLog('ROLE_SAVE', $request);
        DB::beginTransaction();

        Try {
            $inputs = $request->all();
            $result = $this->roleService->store($inputs['parent_code'], $inputs['name'], 'admin', $inputs['seq_no']);

            if ($result['status']) {
                DB::commit();
                $this->updateLog($result);
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            $this->updateLog($result);
            return $this->error($result['message'], 400);
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

    public function edit(string $name): JsonResponse
    {
        Try {
            $result = $this->roleService->getDetails($name);
            if ($result) {
                return $this->success($result['message'], RoleResource::collection([$result['data']]));
            }

            return $this->error($result['message'], 400);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function update(string $id, RolePutRequest $request): JsonResponse
    {
        $this->insertLog("ROLE_UPDATE", $request);
        DB::beginTransaction();

        Try {
            $id = decrypt($id);
            $result = $this->roleService->update(
                $id,
                $request->input('parent_code'),
                $request->input('name'),
                $request->input('seq_no'),
                $request->input('guard_name'));

            if ($result['status']) {
                DB::commit();
                $this->updateLog($result);
                return $this->success($result['message']);
            }

            DB::rollback();
            $this->updateLog($result);
            return $this->error($result['message'], 400);
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

    public function delete(string $id): JsonResponse
    {
        Try {
            $id = decrypt($id);
            $result = $this->roleService->delete($id);
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message'], 400);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
