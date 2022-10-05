<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 29/09/2022
 * Time: 3:47 PM
 */

namespace App\Services;

use App\Interfaces\AnnouncementDetailInterface;
use App\Interfaces\SysLanguageInterface;
use App\Traits\ResponseAPI;

use App\Interfaces\AnnouncementInterface;

class AnnouncementService extends BaseService
{
    use ResponseAPI;

    public $fileService, $announcementDetailInterface, $langInterface;

    public function __construct(
        AnnouncementInterface $interface,
        FileService $fileService,
        SysLanguageInterface $langInterface,
        AnnouncementDetailInterface $announcementDetailInterface
    )
    {
        parent::__construct($interface);
        $this->fileService = $fileService;
        $this->langInterface = $langInterface;
        $this->announcementDetailInterface = $announcementDetailInterface;
    }

    /**
     * @param string $code
     * @param $images
     * @param int $seq_no
     * @param bool $is_popup
     * @param $start_date
     * @param array $options
     * @return array
     */
    public function store(string $code, $images, int $seq_no, bool $is_popup, $start_date, array $options)
    {
        $title = strtolower(removeSpecialCharacters($code));
        $result = $this->interface->findBy('code', $title);
        if ($result) {
            return $this->response(false, 'title_already_exist');
        }

        $file_result = $this->fileService->upload($images, 'images');
        if (!$file_result['status']) {
            return $this->response(false, $file_result['message']);
        }

        $result = $this->interface->create([
            'code' => $title,
            'name' => $code,
            'avatar' => $file_result['data']['filename'] ?? null,
            'seq_no'=> $seq_no,
            'is_popup' => $is_popup,
            'date_start' => $start_date,
            'date_end' => $options['date_end'] ?? null
        ]);
        if (!$result) {
            return $this->response(false, 'failed_to_save');
        }

        $arry_name = $options['content'] ?? [];
        foreach ($arry_name as $key => $value) {
            $lang = $this->langInterface->findBy('locale', $value['lang'], ['*'], []);
            $this->announcementDetailInterface->create([
                'announcement_id' => $result['id'],
                'language_id' => $lang['id'],
                'title' => $value['title'],
                'description' => $value['description']
            ]);
        }

        return $this->response(true, 'record_saved_successfully');
    }
}
