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
        Schema::create('stock_good_receive_items', function (Blueprint $table) {
            $table->unsignedInteger('stock_receive_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('total_in')->default(0);
            $table->unsignedInteger('total_out')->default(0);
            $table->unsignedInteger('balance')->default(0);
            $table->softDeletes();
            $table->primary([
                'stock_receive_id', 'product_id'
            ]);
            $table->index([
                'stock_receive_id',
                'product_id'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_good_receive_items');
    }
};
