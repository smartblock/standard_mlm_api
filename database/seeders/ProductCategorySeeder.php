<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new ProductCategory;
        $category->category_code = "ROOT";
        $category->category_name = "ROOT";
        $category->seq_no = 0;

        $category->save();
    }
}
