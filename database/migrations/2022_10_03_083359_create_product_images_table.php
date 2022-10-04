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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->string('avatar', 255)->nullable();
            $table->double('width')->nullable();
            $table->double('height')->nullable();
            $table->boolean('is_default_image')->nullable();
            $table->string('status', 1)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index([
                'product_id',
                'is_default_image',
                'status',
                'created_by',
                'updated_by'
            ], 'product_image_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
};
