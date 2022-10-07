<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryDetail extends Model
{
    use HasFactory;

    protected $table = "product_category_details";

    protected $primaryKey = "id";

    protected $guarded = [];
}
