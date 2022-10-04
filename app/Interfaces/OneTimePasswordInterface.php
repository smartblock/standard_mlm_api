<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 10:25 AM
 */

namespace App\Interfaces;

interface OneTimePasswordInterface extends EloquentRepositoryInterface
{
    /**
     * @param string $mobile_no
     * @param string $otp_code
     * @return mixed
     */
    public function validate(string $email);
}