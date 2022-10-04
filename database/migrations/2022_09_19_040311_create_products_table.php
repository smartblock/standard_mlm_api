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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('code', 30)->unique();
            $table->string('name', 50);
            $table->string('status', '1')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->string('group', 50)->nullable();
            $table->unsignedFloat('price')->nullable();
            $table->unsignedFloat('bv')->nullable();
            $table->unsignedDecimal('leverage')->nullable();
            $table->string('leverage_type', 25)->nullable();
            $table->unsignedDecimal('weight', 8, 3);
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->unsignedInteger('seq_no')->nullable();
            $table->string('is_custom_label', 50)->nullable();
            $table->string('is_custom_color', 25)->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->index([
                'country_id',
                'code',
                'category_id',
                'group',
                'leverage_type',
                'date_start',
                'date_end',
                'seq_no',
                'created_by',
                'updated_by'
            ], 'product_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
