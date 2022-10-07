<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;

class Admin extends User
{
    use HasRoles;

    protected $table = "users";

    protected $primaryKey = "id";

    protected $guarded = [];

    protected $guard_name = "admin";
}
