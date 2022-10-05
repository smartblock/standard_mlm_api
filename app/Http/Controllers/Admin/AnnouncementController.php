<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Announcement\AnnouncementListRequest;
use App\Http\Resources\Admin\AnnouncementResource;
use DB;
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

    public function index(AnnouncementListRequest $request)
    {
        Try {
            $params = [];
            if ($request->filled('type')) {
                $params['type'] = $request->input('type');
            }

            $result = $this->announcementService->all($request->input('page'), ['*'], $params, [
                'column' => 'seq_no',
                'dir' => 'asc'
            ], ['createdBy', 'updatedBy']);

            return $this->responseTable(AnnouncementResource::collection($result['data']), $result['total'], $request->input('page'), $result['length']);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function edit(string $id)
    {
        Try {
            $result = $this->announcementService->getDetails(decrypt($id), ['createdBy', 'updatedBy', 'details.language']);
            if ($result) {
                $data = AnnouncementResource::collection([$result['data']]);
                return $this->success($result['message'], $data[0]);
            }

            return $this->error($result['message'], 422);
        } Catch (\Throwable $exception) {
            return $this->error($exception);
        }
    }

    public function save(AnnouncementPostRequest $request)
    {
        $this->insertLog("ANNOUNCEMENT_SAVE", $request);
        DB::beginTransaction();

        Try {
            $params = [];
            if ($request->filled('date_end')) {
                $params['date_end'] = $request->input('date_end');
            }

            if ($request->filled('content')) {
                $params['content'] = json_decode($request->input('content'), true);
            }

            $result = $this->announcementService->store(
                $request->input('code'),
                $request->file('avatar'),
                $request->input('seq_no'),
                $request->input('is_popup'),
                $request->input('date_start'),
                $params
            );
            if ($result['status']) {
                DB::commit();
                return $this->success($result['message'], "", 201);
            }

            DB::rollback();
            return $this->error($result['message'], 422);
        } Catch(\Throwable $exception) {
            DB::rollback();
            return $this->error($exception);
        }
    }
}
