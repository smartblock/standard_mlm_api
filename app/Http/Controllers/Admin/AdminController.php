<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\User\AdminListRequest;
use App\Http\Requests\Admin\User\AdminPostRequest;
use App\Http\Requests\Admin\User\AdminPutRequest;

use App\Interfaces\RequestLogInterface;

use App\Http\Resources\Admin\AdminResource;

use App\Services\AdminService;

use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    use ResponseAPI;

    protected $adminService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        AdminService $adminService
    ) {
        parent::__construct($requestLogInterface);
        $this->adminService = $adminService;
    }

    public function index(AdminListRequest $request): JsonResponse
    {
        $this->insertLog("ADMIN_LIST", $request);

        Try {
            $result = $this->adminService->all($request->input('page'), ['*'], [
                'role' => 'admin'
            ]);

            return $this->responseTable(AdminResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error($exception->getMessage());
        }
    }

    public function save(AdminPostRequest $request): JsonResponse
    {
        $this->insertLog('ADMIN_SAVE', $request);
        DB::beginTransaction();

        Try {
            $result = $this->adminService->store(
                $request->input('username'),
                $request->input('password'),
                $request->input('email'),
                $request->input('name'),
                $request->input('role'));

            if ($result['status']) {
                DB::commit();
                $this->updateLog($result);
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            $this->updateLog($result);
            return $this->error($result['message'], 422);
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

    public function edit(string $id): JsonResponse
    {
        Try {
            $id = decrypt($id);
            $result = $this->adminService->getDetails($id);
            if ($result['status']) {
                $data = AdminResource::collection([$result['data']]);
                return $this->success($result['message'], $data[0]);
            }

            return $this->error($result['message'], 422);
        } catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function update(string $id, AdminPutRequest $request): JsonResponse
    {
        $this->insertLog("ADMIN_UPDATE", $request);
        DB::beginTransaction();

        Try {
            $id = decrypt($id);
            $result = $this->adminService->update(
                $id,
                $request->input('username'),
                $request->input('name'),
                $request->input('email'),
                $request->input('status'),
                $request->input('password') ?? '',
                $request->input('role'), []);
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

    public function delete(string $id): JsonResponse
    {
        Try {
            $id = decrypt($id);
            $result = $this->adminService->delete($id);
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
