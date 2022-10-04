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
        Schema::create('stock_locations', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code', 50)->nullable();
            $table->string('stock_name', 255)->nullable();
            $table->boolean('is_default')->default(0);
            $table->integer('seq_no')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index([
                'stock_code',
                'is_default',
                'seq_no'
            ], 'stock_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_locations');
    }
};
