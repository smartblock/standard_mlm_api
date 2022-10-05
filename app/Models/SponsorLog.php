<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorLog extends Model
{
    use HasFactory;

    protected $table = "sponsor_logs";

    protected $primaryKey = "id";

    protected $guarded = [];
}
