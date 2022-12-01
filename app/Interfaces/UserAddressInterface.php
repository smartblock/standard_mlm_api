<?php

namespace App\Interfaces;

interface UserAddressInterface extends EloquentRepositoryInterface
{
    /**
     * @param int $user_id
     * @param string $address_type
     * @return mixed
     */
    public function removeShippingStatus(int $user_id, string $address_type);
}