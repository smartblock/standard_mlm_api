<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 14/09/2022
 * Time: 4:24 PM
 */

namespace App\Services;

use App\Interfaces\SettingInterface;
use App\Interfaces\SysCountryInterface;

class GeneralSettingService extends BaseService
{
    protected $countryInterface;

    public function __construct(
        SettingInterface $interface,
        SysCountryInterface $countryInterface
    )
    {
        parent::__construct($interface);
        $this->countryInterface = $countryInterface;
    }

    public function all(int $page, array $columns = ['*'], array $params = [], array $order = [], array $relations = [])
    {
        if (!empty($params['limit'])) {
            $this->interface->setPerPage($params['limit']);
            unset($params['limit']);
        }

        $limit = $this->interface->perPage();
        $start = $page == 1 ? 0 : --$page * $limit;

        $result = $this->interface->pagination($start, $limit, $columns, $params, $order, $relations, [], true);

        return $this->responsePaginate($limit, $result);
    }

    public function store(string $type, string $code, string $name, int $seq_no)
    {
        $validate = $this->interface->validate($type, $code);
        if ($validate) {
            return $this->response(false, 'record_already_exist');
        }

        $result = $this->interface->create([
            'country_id' => 1,
            'type' => $type,
            'code' => $code,
            'name' => $name,
            'seq_no' => $seq_no
        ]);

        if (!$result) {
            return $this->response(false, 'failed_to_create_record');
        }

        return $this->response(true, 'record_created_successfully');
    }

    public function getDetailsByCode(string $type, string $code)
    {
        $result = $this->interface->validate($type, $code);
        if ($result) {
            return $this->response(true, 'success', $result);
        }

        return $this->response(false, 'invalid_request');
    }

    public function update(int $id, string $country, string $name, string $status, int $seq_no)
    {
        $result = $this->interface->findBy('id', $id, ['*'], [], true);
        if (!$result) {
            return $this->response(false, 'invalid_request');
        }

        $country = $this->countryInterface->findBy('code', $country, ['id']);
        $result['country_id'] = $country['id'];
        $result['name'] = $name;
        $result['seq_no'] = $seq_no;
        $result->save();

        if (!$result) {
            return $this->response(false, 'failed_to_update_record');
        }

        if ($status == "I") {
            if (empty($result->deleted_at)) {
                if (!$result->delete()) {
                    return $this->response(false, 'failed_to_update_record');
                }
            }
        } else {
            if (!$result->restore()) {
                return $this->response(false, 'failed_to_update_record');
            }
        }

        return $this->response(true, 'record_updated_successfully');
    }
}