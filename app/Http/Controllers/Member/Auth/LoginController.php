<?php

namespace App\Http\Controllers\Member\Auth;

use App\Http\Controllers\Controller;

use App\Traits\ResponseAPI;

use App\Http\Requests\Member\Auth\MemberLoginRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\Auth\AuthMemberService;

class LoginController extends Controller
{
    use ResponseAPI;

    private $authService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        AuthMemberService $authService
    )
    {
        parent::__construct($requestLogInterface);
        $this->authService = $authService;
    }

    public function login(MemberLoginRequest $request)
    {
        $this->insertLog('MEMBER_LOGIN', $request);

        Try {
            $result = $this->authService->authenticate('member', $request->input('username'), $request->input('password'));
            if ($result['status']) {
                $this->updateLog($result['message']);
                return $this->success($result['message'], $result['data']);
            }

            $this->updateLog($result['message']);
            return $this->error($result['message'], 401);
        } Catch (\Exception $exception) {
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error();
        }
    }

    public function resetPassword()
    {

    }
}
