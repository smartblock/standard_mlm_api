<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use App\Interfaces\RequestLogInterface;
use App\Services\ProductService;
use App\Traits\ResponseAPI;

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

    public function index(string $group)
    {
        $products = $this->productService->listAll(['*'], [], [
            'group' => strtoupper($group)
        ], []);

        return $this->success('success', $products);
    }
}
