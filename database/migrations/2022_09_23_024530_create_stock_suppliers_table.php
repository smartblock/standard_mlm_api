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
        Schema::create('stock_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 25);
            $table->string('name', 255);
            $table->integer('seq_no')->nullable();
            $table->string('status', 25);
            $table->softDeletes();
            $table->index([
                'code',
                'seq_no',
                'status'
            ], 'supplier_index');
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
        Schema::dropIfExists('stock_suppliers');
    }
};
