<?php

namespace App\Http\Controllers\Member\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\Auth\ForgotPasswordPostRequest;
use App\Interfaces\RequestLogInterface;
use App\Services\Auth\AuthMemberService;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use ResponseAPI;

    public $authService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        AuthMemberService $authService
    )
    {
        parent::__construct($requestLogInterface);
        $this->authService = $authService;
    }

    public function save(ForgotPasswordPostRequest $request)
    {
        Try {
            $result = $this->authService->resetPassword($request->input('email'));
            if ($result['status']) {
                return $this->success($result['message'], "", 201);
            }

            return $this->error($result['message']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function postResetPassword(ResetPasswordPostRequest $request)
    {
        Try {
            $result = $this->authService->resetPasswordSubmission(
                $request->input('token'),
                $request->input('password'),
                $request->input('password_confirmation')
            );
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
