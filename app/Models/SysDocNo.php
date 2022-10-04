<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysDocNo extends Model
{
    use HasFactory;

    protected $table = "sys_doc_numbers";

    protected $primaryKey = "id";

    protected $guarded = [];
}
