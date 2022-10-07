<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class UserRole extends Role
{
    use HasFactory, SoftDeletes;

    public $guard_name = "api";

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->orderBy('seq_no', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id')
            ->orderBy('seq_no', 'asc');
    }
}
