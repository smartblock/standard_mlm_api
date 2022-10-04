<?php

namespace App\Interfaces;

interface SettingInterface extends EloquentRepositoryInterface
{
    /**
     * @param string $type
     * @param string $name
     * @return mixed
     */
    public function validate(string $type, string $name);


}