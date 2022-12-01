<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use DB;
use App\Traits\ResponseAPI;

use App\Interfaces\RequestLogInterface;

use App\Http\Resources\Member\AnnouncementResource;

use App\Services\AnnouncementService;

class AnnouncementController extends Controller
{
    use ResponseAPI;

    private $announcementService;

    public function __construct(
        RequestLogInterface $requestLogInterface,
        AnnouncementService $announcementService
    )
    {
        parent::__construct($requestLogInterface);
        $this->announcementService = $announcementService;
    }

    public function index()
    {
        Try {
            $params['type'] = 'dashboard';

            $result = $this->announcementService->listAll(['*'], [], $params, [
                'column' => 'date_start',
                'dir' => 'desc'
            ]);

            return $this->success('success', AnnouncementResource::collection($result));
        } Catch (\Throwable $exception) {
            return $this->error();
        }
    }
}
