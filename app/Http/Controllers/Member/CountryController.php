<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Resources\Member\CountryResource;
use App\Interfaces\RequestLogInterface;
use App\Services\CountryService;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    use ResponseAPI;

    private $countryService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        CountryService $countryService
    )
    {
        parent::__construct($requestLogInterface);
        $this->countryService = $countryService;
    }

    public function index()
    {
        $result = $this->countryService->listAll(['*'], [], [], [
            'column' => 'id',
            'dir' => 'asc'
        ]);

        return $this->success('success', CountryResource::collection($result));
    }
}
