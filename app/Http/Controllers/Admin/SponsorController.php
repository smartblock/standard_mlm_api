<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Member\ChangeSponsorPostRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\MemberService;

class SponsorController extends Controller
{
    use ResponseAPI;

    private $memberService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        MemberService $memberService
    )
    {
        parent::__construct($requestLogInterface);
        $this->memberService = $memberService;
    }

    public function save(ChangeSponsorPostRequest $request)
    {
        $this->insertLog("CHANGE_SPONSOR_SAVE", $request);
        DB::beginTransaction();

        Try {
            $result = $this->memberService->changeSponsor($request->input('username'), $request->input('new_sponsor'));
            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message']);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }
}
