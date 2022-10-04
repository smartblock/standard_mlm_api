<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackageItem extends Model
{
    use HasFactory;

    protected $table = "product_package_items";

    protected $primaryKey = "id";

    protected $guarded = [];
}
