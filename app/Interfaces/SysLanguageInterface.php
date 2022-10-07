<?php

namespace App\Interfaces;

interface SysLanguageInterface extends EloquentRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getDefaultLanguage();
}