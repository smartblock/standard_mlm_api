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
        Schema::create('wallet_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('wallet_id');
            $table->unsignedDecimal('total_in', 25, 10)->default(0);
            $table->unsignedDecimal('total_out', 25, 10)->default(0);
            $table->unsignedDecimal('balance', 25, 10)->default(0);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->index([
                'user_id',
                'wallet_id',
                'created_by',
                'updated_by'
            ], 'wallet_balance_index');
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
        Schema::dropIfExists('wallet_summaries');
    }
};
