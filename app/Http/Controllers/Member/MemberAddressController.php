<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\Address\AddressPostRequest;
use App\Interfaces\RequestLogInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class MemberAddressController extends Controller
{
    use ResponseAPI;

    public function __construct(RequestLogInterface $requestLogInterface)
    {
        parent::__construct($requestLogInterface);
    }

    public function getAddresses(AddressPostRequest $request)
    {
        Try {

        } Catch (\Throwable $exception) {

        }
    }
}
