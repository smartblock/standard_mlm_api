<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Product\PackagePostRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\ProductService;

class PackageController extends Controller
{
    use ResponseAPI;

    private $productService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        ProductService $productService
    )
    {
        parent::__construct($requestLogInterface);
        $this->productService = $productService;
    }

    public function index()
    {

    }

    public function save(PackagePostRequest $request)
    {
        $this->insertLog("PACKAGE_SAVE", $request);
        DB::beginTransaction();

        Try {
            $params = [];
            if ($request->filled('desc')) {
                $params['desc'] = $request->input('desc');
            }

            if ($request->filled('bv')) {
                $params['bv'] = $request->input('bv');
            }

            if ($request->filled('desc')) {
                $params['desc'] = $request->input('desc');
            }

            if ($request->filled('weight')) {
                $params['weight'] = $request->input('weight');
            }

            if ($request->filled('seq_no')) {
                $params['seq_no'] = $request->input('seq_no');
            }

            $result = $this->productService->savePackageItem(
                json_decode($request->input('product'), true),
                $request->input('code'),
                $request->input('category_code'),
                $request->input('price'),
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
            return $this->error($exception);
        }
    }
}
