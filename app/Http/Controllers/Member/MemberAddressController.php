<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use Auth;
use App\Traits\ResponseAPI;

use App\Http\Requests\Member\Address\AddressPostRequest;
use App\Http\Requests\Member\Address\AddressListRequest;

use App\Interfaces\RequestLogInterface;

use App\Http\Resources\Member\AddressResource;

use App\Services\MemberAddressService;

class MemberAddressController extends Controller
{
    use ResponseAPI;

    protected $addressService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        MemberAddressService $addressService
    )
    {
        parent::__construct($requestLogInterface);
        $this->addressService = $addressService;
    }

    /**
     * @param AddressPostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(AddressPostRequest $request)
    {
        Try {
            $inputs = $request->all();
            $result = $this->addressService->save(
                Auth::user()->id,
                $inputs['country_code'],
                $inputs['recipient_name'],
                $inputs['address'],
                $inputs['is_default_shipping_address'],
                $inputs['is_default_billing_address'],
                [
                    'address_type' => ($inputs['is_default_shipping_address'] == 1) ? "SHIPPING" : "NORMAL",
                    'postcode' => $inputs['postcode'],
                    'state' => $inputs['state'],
                    'label' => $inputs['label'],
                    'email' => $inputs['email']
                ]);
            if (!$result['status']) {
                return $this->error($result['message'], 422, $result['data']);
            }

            return $this->success($result['message'], $result['data'], 201);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    /**
     * @param AddressListRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAddresses(AddressListRequest $request)
    {
        Try {
            $result = $this->addressService->listAll(['*'], ['country'], [
                'user_id' => Auth::user()->id
            ], [
                'column' => 'is_default_shipping',
                'dir' => 'desc'
            ]);

            return $this->success('success', AddressResource::collection($result));
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        Try {
            $decrypted_id = decrypt($id);

            $result = $this->addressService->delete($decrypted_id);
            if (!$result['status']) {
                return $this->error($result['message'], 422, $result['data']);
            }

            return $this->success($result['message'], $result['data']);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }

    public function edit(string $id)
    {
        Try {
            $decrypted_id = decrypt($id);

            $result = $this->addressService->edit($decrypted_id, Auth::user()->id);
            if (!$result['status']) {
                return $this->error($result['message'], 422, $result['data']);
            }

            $data = AddressResource::collection([$result['data']]);
            return $this->success($result['message'], $data[0]);
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
