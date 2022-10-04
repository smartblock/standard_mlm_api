<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Product\MixMatchPostRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\ProductMixMatchService;

class ProductMixMatchController extends Controller
{
    use ResponseAPI;

    private $mixMatchService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        ProductMixMatchService $mixMatchService
    )
    {
        parent::__construct($requestLogInterface);
        $this->mixMatchService = $mixMatchService;
    }

    public function index()
    {

    }

    public function save(MixMatchPostRequest $request)
    {
        $this->insertLog("PRODUCT_MIX_MATCH_SAVE", $request);
        DB::beginTransaction();

        Try {
            $params = [];
            if ($request->filled('status')) {
                $params['status'] = $request->input('status');
            }

            if ($request->filled('status')) {
                $params['seq_no'] = $request->input('seq_no');
            }

            if ($request->filled('name')) {
                $params['name'] = $request->input('name');
            }

            if ($request->filled('date_start')) {
                $params['date_start'] = $request->input('date_start');
            }

            if ($request->filled('date_end')) {
                $params['date_end'] = $request->input('date_end');
            }

            $result = $this->mixMatchService->storeMixMatch(
                $request->input('category_code'),
                $request->input('code'),
                json_decode($request->input('product'), true),
                $request->input('price'),
                $request->input('quantity'),
                $params
            );
            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }
}
