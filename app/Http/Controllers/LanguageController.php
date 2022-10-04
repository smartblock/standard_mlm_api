<?php

namespace App\Http\Controllers;

use App\Http\Resources\LanguageResource;
use App\Interfaces\RequestLogInterface;
use App\Interfaces\SysLanguageInterface;
use App\Services\SysLanguageService;
use App\Traits\ResponseAPI;

class LanguageController extends Controller
{
    use ResponseAPI;

    private $langService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        SysLanguageService $langService
    )
    {
        parent::__construct($requestLogInterface);
        $this->langService = $langService;
    }

    public function index()
    {
        $lang = $this->langService->listAll(['*'], [], [], [
            'column' => 'seq_no',
            'dir' => 'asc'
        ]);

        return $this->success('success', LanguageResource::collection($lang));
    }
}
