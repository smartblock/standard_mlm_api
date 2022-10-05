<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 03/10/2022
 * Time: 6:35 PM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

class FileService
{
    use ResponseAPI;

    public function upload($file, string $target_path)
    {
        $filename = $file->getClientOriginalName();
        $name = pathinfo($filename,PATHINFO_FILENAME).'_'.time().'.'.$file->extension();

        $result = $file->move(public_path($target_path), $name);
        if (!$result) {
            return $this->response(false, 'failed_to_upload');
        }

        return $this->response(true, 'uploaded_successfully', [
            'filename' => $name
        ]);
    }
}