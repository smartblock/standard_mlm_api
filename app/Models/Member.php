<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;

class Member extends User
{
    use HasRoles;

    protected $table = "users";

    protected $primaryKey = "id";

    protected $guarded = [];

    protected $guard_name = "api";
}
