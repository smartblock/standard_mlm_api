<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Auth;

use App\Interfaces\RequestLogInterface;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $requestLogInterface;

    public function __construct(RequestLogInterface $requestLogInterface)
    {
        $this->requestLogInterface = $requestLogInterface;
    }

    /**
     * @param string $action_type
     * @param $request
     */
    public function insertLog(string $action_type, $request)
    {
        $this->requestLogInterface->insertLog(
            Auth::user()->id ?? 0,
            $action_type,
            $request->getRequestUri(),
            $request->all());
    }

    /**
     * @param $response
     */
    public function updateLog($response)
    {
        $this->requestLogInterface->updateResponse($response);
    }
}
