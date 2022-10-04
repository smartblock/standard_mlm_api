<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 11:32 AM
 */

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\SysLanguageInterface;

class SysLanguageService extends BaseService
{
    use ResponseAPI;

    protected $interface;

    public function __construct(SysLanguageInterface $interface)
    {
        parent::__construct($interface);
    }

    /**
     * @param string $locale
     * @param string $code
     * @param string $name
     * @param int $seq_no
     * @return array
     */
    public function store(string $locale, string $code, string $name, int $seq_no)
    {
        $result = $this->interface->create([
            'locale' => $locale,
            'code' => $code,
            'name' => $name,
            'seq_no' => $seq_no,
        ]);

        if (!$result) {
            return $this->response(false, 'failed_to_create_record');
        }

        return $this->response(true, 'record_created_successfully');
    }

    /**
     * @param int $id
     * @param string $locale
     * @param string $code
     * @param string $name
     * @param string $status
     * @param int $seq_no
     * @param array $param
     * @return array
     */
    public function update(int $id, string $locale, string $code, string $name, int $seq_no)
    {
        $result = $this->interface->find($id, true);
        if (!$result) {
            return $this->response(false, 'invalid_record');
        }

        $result['locale'] = $locale;
        $result['code'] = $code;
        $result['name'] = $name;
        $result['seq_no'] = $seq_no;

        if ($result->save()) {
            return $this->response(true, 'record_updated_successfully');
        }

        return $this->response(false, 'failed_to_update');
    }
}
