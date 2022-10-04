<?php
/**
 * Created by PhpStorm.
 * User: chan
 * Date: 30/09/2022
 * Time: 12:23 PM
 */

namespace App\Services;

use App\Interfaces\SysCountryInterface;

class CountryService extends BaseService
{
    public function __construct(SysCountryInterface $interface)
    {
        parent::__construct($interface);
    }
}