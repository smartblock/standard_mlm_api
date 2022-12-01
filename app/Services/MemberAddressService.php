<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 6:00 PM
 */

namespace App\Services;

use App\Interfaces\EloquentRepositoryInterface;

use App\Interfaces\SysCountryInterface;
use App\Interfaces\UserAddressInterface;
use App\Traits\ResponseAPI;

class MemberAddressService extends BaseService
{
    use ResponseAPI;

    protected $countryInterface;

    public function __construct(
        UserAddressInterface $interface,
        SysCountryInterface $countryInterface
    )
    {
        parent::__construct($interface);
        $this->countryInterface = $countryInterface;
    }

    public function save(int $user_id, string $country_code, string $receipent_name, string $address, $is_shipping_address = 0, $is_billing_address = 0, array $options = [])
    {
        $country = $this->countryInterface->findBy('code', $country_code, ['*'], []);
        if (!$country_code) {
            return $this->response(false, 'invalid_country');
        }

        if ($is_shipping_address == 1) {
            $this->interface->removeShippingStatus($user_id, 'SHIPPING');
            $this->interface->removeShippingStatus($user_id, 'BILLING');
        }

        $result = $this->interface->create([
            'user_id' => $user_id,
            'address_type' => $options['address_type'],
            'name' => $receipent_name,
            'email' => $options['email'],
            'address1' => $address,
            'country_id' => $country['id'],
            'postcode' => $options['postcode'],
            'state_id' => $options['state'] ?? null,
            'is_default_billing' => $is_billing_address,
            'is_default_shipping' => $is_shipping_address
        ]);
        if ($result) {
            return $this->response(true, 'address_saved_successfully');
        }

        return $this->response(false, 'failed_to_save_address');
    }

    /**
     * @param int $id
     * @return array
     */
    public function delete(int $id)
    {
        $result = $this->interface->deleteById($id);
        if ($result) {
            return $this->response(true, 'record_deleted_successfully');
        }

        return $this->response(false, 'failed_to_delete');
    }

    public function edit(int $id, int $user_id)
    {
        $result = $this->interface->findBy('id', $id);
        if ($result) {
            if ($result['user_id'] != $user_id) {
                return $this->response(false, 'invalid_record');
            }

            return $this->response(true, 'success', $result);
        }

        return $this->response(false, 'failed');
    }
}
