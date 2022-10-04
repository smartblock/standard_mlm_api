<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\User\AdminLoginRequest;

use App\Interfaces\RequestLogInterface;

use App\Services\Auth\AuthService;

use App\Traits\ResponseAPI;

class LoginController extends Controller
{
    use ResponseAPI;

    protected $authService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        AuthService $authService
    )
    {
        parent::__construct($requestLogInterface);
        $this->authService = $authService;
    }

    public function login(AdminLoginRequest $request)
    {
        $this->insertLog('LOGIN', $request);

        Try {
            $result = $this->authService->authenticate('admin', $request->input('username'), $request->input('password'));
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
}
