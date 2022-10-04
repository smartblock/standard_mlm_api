<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use App\Interfaces\RequestLogInterface;

class MemberController extends Controller
{
    public function __construct(RequestLogInterface $requestLogInterface)
    {
        parent::__construct($requestLogInterface);
    }
}
