<?php

namespace App\Http\Controllers\Member\Auth;

use App\Http\Controllers\Controller;

use App\Traits\ResponseAPI;
use DB;

use App\Interfaces\RequestLogInterface;

use App\Http\Requests\Member\MemberPostRequest;
use App\Services\MemberService;


class RegisterController extends Controller
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

    public function save(MemberPostRequest $request)
    {
        $this->insertLog("MEMBER_REGISTER", $request);
        DB::beginTransaction();

        Try {
            $inputs = $request->all();
            $result = $this->memberService->store($inputs['country_code'], $inputs['sponsor'], $inputs['identity_no'], $inputs['password'], $inputs['email'], [
                'name' => $inputs['name'],
                'mobile_no' => $inputs['mobile_no'],
                'gender' => $inputs['gender'],
                'dob' => $inputs['dob']
            ]);

            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message'], 400);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function validateSponsor(string $sponsor)
    {
        $details = $this->memberService->validateSponsor($sponsor);

        if (!$details['status']) {
            return $this->error($details['message'], 422);
        }

        return $this->success('success', [
            'sponsor_name' => $details['data']['name']
        ]);
    }
}
