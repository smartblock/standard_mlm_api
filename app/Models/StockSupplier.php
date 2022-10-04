<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockSupplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "stock_suppliers";

    protected $primaryKey = "id";

    protected $guarded = [];
}
