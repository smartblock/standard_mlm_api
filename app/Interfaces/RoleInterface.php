<?php

namespace App\Interfaces;

interface RoleInterface extends EloquentRepositoryInterface
{
    /**
     * @param string $code
     * @param string $guard_name
     * @return mixed
     */
    public function getRoleByCode(string $code, string $guard_name);

    /**
     * @param string $name
     * @param string $guard_name
     * @param int $id
     * @return mixed
     */
    public function validate(string $name, string $guard_name, int $id);
}
