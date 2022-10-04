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
        Schema::create('wallet_details', function (Blueprint $table) {
            $table->id();
            $table->string('trans_date')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('wallet_id');
            $table->string('trans_type', 35)->nullable();
            $table->unsignedDecimal('total_in', 25, 10)->default(0);
            $table->unsignedDecimal('total_out', 25, 10)->default(0);
            $table->unsignedDecimal('balance', 25, 10)->default(0);
            $table->text('remark')->nullable();
            $table->text('additional_msg')->nullable();
            $table->unsignedInteger('sales_id')->nullable();
            $table->unsignedInteger('topup_id')->nullable();
            $table->unsignedInteger('transfer_id')->nullable();
            $table->unsignedInteger('withdraw_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->index([
                'trans_date',
                'user_id',
                'wallet_id',
                'trans_type',
                'sales_id',
                'topup_id',
                'transfer_id',
                'withdraw_id',
                'created_by',
                'updated_by'
            ], 'balance_index');
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
        Schema::dropIfExists('wallet_details');
    }
};
