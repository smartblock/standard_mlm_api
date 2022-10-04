<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use App\Interfaces\RequestLogInterface;

use App\Http\Requests\Member\Otp\OtpPostRequest;

use App\Services\OtpService;
use App\Traits\ResponseAPI;

class OtpController extends Controller
{
    use ResponseAPI;

    private $otpService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        OtpService $otpService
    )
    {
        parent::__construct($requestLogInterface);
        $this->otpService = $otpService;
    }

    public function save(OtpPostRequest $request)
    {
        Try {
            $result = $this->otpService->sendOtp($request->input('email'));
            if (!$result['status']) {
                return $this->error($result['message'], 422, $result['data']);
            }

            return $this->success($result['message'], $result['data'], 201);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function validateOTP(string $email, string $otp)
    {
        Try {
            $result = $this->otpService->validateOtp($email, $otp);
            if (!$result['status']) {
                return $this->error($result['message'], 422);
            }

            return $this->success($result['message'], $result['data']);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }
}
