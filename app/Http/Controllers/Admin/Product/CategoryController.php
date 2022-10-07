<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ProductCategory\CategoryPutRequest;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\ProductCategory\CategoryListRequest;
use App\Http\Requests\Admin\ProductCategory\CategoryPostRequest;

use App\Http\Resources\Admin\ProductCategoryResource;

use App\Interfaces\RequestLogInterface;

use App\Services\ProductCategoryService;

class CategoryController extends Controller
{
    use ResponseAPI;

    CONST ACTION_TYPE = "PRODUCT_CATEGORY";

    private $productCategoryService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        ProductCategoryService $productCategoryService
    )
    {
        parent::__construct($requestLogInterface);
        $this->productCategoryService = $productCategoryService;
    }

    public function save(CategoryPostRequest $request)
    {
        $this->insertLog(self::ACTION_TYPE."_SAVE", $request);

        Try {
            $result = $this->productCategoryService->store(
                $request->input('parent'),
//                $request->input('category_code'),
                json_decode($request->input('category_name'), true),
                $request->input('status'),
                $request->input('seq_no')
            );
            if ($result['status']) {
                return $this->success($result['message'], "", 201);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function edit(string $code)
    {
        Try {
            $id = decrypt($code);
            $details = $this->productCategoryService->getDetails($id);

            $data = ProductCategoryResource::collection([$details['data']]);
            return $this->success('success', $data[0]);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function all(CategoryListRequest $request)
    {
        Try {
            $params = [];
            if ($request->filled('parent_code')) {
                $params['parent_code'] = $request->input('parent_code');
            }

            $details = $this->productCategoryService->listAll([
                'id', 'category_code', 'category_name', 'status', 'seq_no'
            ], [], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ]);
            return $this->success('success', ProductCategoryResource::collection($details));
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function update(string $id, CategoryPutRequest $request)
    {
        $this->insertLog(self::ACTION_TYPE."_UPDATE", $request);

        Try {
            $params = [];
            if ($request->filled('seq_no')) {
                $params['seq_no'] = $request->input('seq_no');
            }

            $category_id = decrypt($id);
            $result = $this->productCategoryService->update(
                $category_id,
//                $request->input('parent'),
//                $request->input('category_code'),
                json_decode($request->input('category_name'), true),
                $request->input('status'),
                $params);

            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function delete(string $id)
    {
        Try {
            $id = decrypt($id);
            $result = $this->productCategoryService->delete($id);
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message'], 400);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
