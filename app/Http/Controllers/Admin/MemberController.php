<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Traits\ResponseAPI;

use App\Http\Requests\Admin\Member\MemberListRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\MemberService;

use App\Http\Resources\Admin\MemberResource;

class MemberController extends Controller
{
    use ResponseAPI;

    CONST ROLE = "member";

    private $memberService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        MemberService $memberService
    )
    {
        parent::__construct($requestLogInterface);
        $this->memberService = $memberService;
    }

    public function index(MemberListRequest $request)
    {
        $this->insertLog("MEMBER_LIST", $request);

        Try {
            $params['role'] = self::ROLE;
            if ($request->filled('username')) {
                $params['username'] = $request->input('username');
            }

            if ($request->filled('email')) {
                $params['username'] = $request->input('email');
            }

            if ($request->filled('name')) {
                $params['username'] = $request->input('name');
            }

            if ($request->filled('ic_no')) {
                $params['username'] = $request->input('ic_no');
            }

            if ($request->filled('limit')) {
                $params['limit'] = $request->input('limit');
            }

            $result = $this->memberService->all($request->input('page'), ['*'], $params, [], [
                'country', 'profile'
            ]);

            return $this->responseTable(MemberResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error($exception);
        }
    }

    public function save()
    {

    }
}
