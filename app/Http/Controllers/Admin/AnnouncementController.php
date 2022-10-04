<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Traits\ResponseAPI;

use App\Interfaces\RequestLogInterface;

use App\Http\Requests\Admin\Announcement\AnnouncementPostRequest;

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

    }

    public function save(AnnouncementPostRequest $request)
    {
        $this->insertLog("ANNOUNCEMENT_SAVE", $request);
        Try {
            $params = [];
            $result = $this->announcementService->store(
                $request->input('code'),
                $request->file('images'),
                $request->input('seq_no'),
                $request->input('is_popup'),
                $params
            );
        } Catch(\Throwable $exception) {

        }
    }
}
