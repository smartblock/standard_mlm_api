<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 29/09/2022
 * Time: 3:47 PM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\AnnouncementInterface;

class AnnouncementService extends BaseService
{
    use ResponseAPI;

    public function __construct(AnnouncementInterface $interface)
    {
        parent::__construct($interface);
    }

    public function store(string $code, $images, int $seq_no, bool $is_popup)
    {
        $title = strtolower(removeSpecialCharacters($code));
        $result = $this->interface->findBy('code', $title);
        if ($result) {
            return $this->response(false, 'title_already_exist');
        }

        $this->interface->create([
            'code' => $title
        ]);
    }
}
