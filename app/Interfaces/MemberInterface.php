<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 28/07/2022
 * Time: 10:25 AM
 */

namespace App\Interfaces;

interface MemberInterface extends UserInterface
{
    /**
     * @param string $email
     * @return mixed
     */
    public function validateEmail(string $email);
}
