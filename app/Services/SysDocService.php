<?php

namespace App\Services;

use App\Traits\ResponseAPI;

use App\Interfaces\SysDocNoInterface;

class SysDocService extends BaseService
{
    use ResponseAPI;

    protected $interface;

    public function __construct(SysDocNoInterface $interface)
    {
        $this->interface = $interface;
    }

    public function getDetailByType(string $doc_type)
    {
        $doc = $this->interface->findBy('doc_type', $doc_type, ['*'], [], true);
        if (!$doc) {
            return $this->response(false, 'invalid_doc_type');
        }

        return $this->response(true, 'success', $doc);
    }

    public function getRunningNo(string $doc_type)
    {
        $doc = $this->interface->findBy('doc_type', $doc_type, ['*'], [], true);
        if (!$doc) {
            return $this->response(false, 'invalid_doc_type');
        }

        return $this->response(true, 'success', $doc);
    }
}
