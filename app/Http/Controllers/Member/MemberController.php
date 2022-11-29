<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use App\Http\Requests\Member\Profile\ProfilePostRequest;
use App\Http\Requests\Member\Profile\UpdatePasswordPostRequest;
use App\Traits\ResponseAPI;
use Auth;
use Illuminate\Http\Request;
use Validator;

use App\Interfaces\RequestLogInterface;

use App\Http\Requests\Member\Profile\UpdateEmailPostRequest;
use App\Http\Requests\Member\Profile\UpdateMobilePostRequest;

use App\Http\Resources\ProfileResource;

use App\Services\MemberService;

class MemberController extends Controller
{
    use ResponseAPI;

    public $memberService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        MemberService $memberService
    )
    {
        parent::__construct($requestLogInterface);
        $this->memberService = $memberService;
    }

    /**
     * Get profile details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        Try {
            $user = Auth::user();
            $data = ProfileResource::collection([$user]);
            return $this->success('success', $data[0]);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    /**
     * Update profile details
     *
     * @param ProfilePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(ProfilePostRequest $request)
    {
        Try {
            $action_type = strtolower($request->input('action_type'));

            $update_field = "";
            switch ($action_type) {
                case 'mobile':
                    $rules = new UpdateMobilePostRequest;
                    $update_field = $request->input('mobile_no');
                    break;
                case 'email':
                    $rules = new UpdateEmailPostRequest;
                    $update_field = $request->input('email');
                    break;
            }

            $validator = Validator::make($request->all(), $rules->rules());
            if ($validator->fails()) {
                $error_message = "";
                foreach ($validator->messages()->get('*') as $key => $value) {
                    $error_message .= $value[0];
                }

                return $this->errorWithoutTranslation($error_message);
            }

            $result = $this->memberService->updateProfile($action_type, Auth::user()->id, $request->input('password'), $update_field);
            if ($result['status']) {
                return $this->success('success');
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    /**
     * Change password
     *
     * @param UpdatePasswordPostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(UpdatePasswordPostRequest $request)
    {
        Try {
            $this->insertLog("CHANGE_PASSWORD", $request);

            $result = $this->memberService->changePassword(
                Auth::user()->id,
                $request->input('current_password'),
                $request->input('password'),
                $request->input('password_confirmation')
            );
            if ($result['status']) {
                return $this->success($result['message']);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            $this->updateLog([
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ]);
            return $this->error();
        }
    }
}
