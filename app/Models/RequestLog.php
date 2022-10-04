<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestLog extends BaseModel
{
    use HasFactory;

    protected $table = "request_logs";

    protected $primaryKey = "id";

    protected $guarded = [];
}
