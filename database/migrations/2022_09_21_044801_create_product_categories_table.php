<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('category_code', 25)->nullable();
            $table->string('category_name', 50)->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('seq_no')->nullable();
            $table->string('status', 1)->default('A');
            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->index([
                'country_id',
                'category_code',
                'parent_id',
                'seq_no',
                'status',
                'created_by',
                'updated_by'
            ], 'product_category_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
};
