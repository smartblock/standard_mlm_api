<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\ProductPostRequest;
use App\Interfaces\RequestLogInterface;
use App\Services\ProductService;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class ProductController extends Controller
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

    public function save(ProductPostRequest $request)
    {
        $this->insertLog("PRODUCT_SAVE", $request);
        Try {
            $params = [];
            if ($request->filled('description')) {
                $params['description'] = $request->input('description');
            }

            if ($request->filled('bv')) {
                $params['bv'] = $request->input('bv');
            }

            if ($request->filled('seq_no')) {
                $params['seq_no'] = $request->input('seq_no');
            }

            if ($request->filled('weight')) {
                $params['weight'] = $request->input('weight');
            }

            if ($request->filled('delivery_group')) {
                $params['delivery_group'] = $request->input('delivery_group');
            }

            if ($request->filled('variant')) {
                $params['variant'] = json_decode($request->input('variant'), true);
            }

            if ($request->hasFile('images')) {
                $params['images'] = $request->file('images');
            }

            $result = $this->productService->store(
                $request->input('category'),
                $request->input('code'),
                $request->input('price'),
                $request->input('status'),
                $params
            );
            if ($result['status']) {
                return $this->success($result['message'], "", 201);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error($exception->getMessage());
        }
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
