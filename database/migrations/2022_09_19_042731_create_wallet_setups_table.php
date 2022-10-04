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
        Schema::create('wallet_setups', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('code', 25)->unique();
            $table->boolean('is_allowed_admin')->nullable();
            $table->boolean('is_allowed_member')->nullable();
            $table->boolean('is_allowed_topup')->default(0);
            $table->unsignedInteger('decimal_length')->default(2);
            $table->integer('seq_no')->nullable();
            $table->unsignedDecimal('topup_min')->nullable();
            $table->unsignedDecimal('topup_max')->nullable();
            $table->boolean('is_allowed_transfer')->default(0);
            $table->unsignedDecimal('transfer_min')->nullable();
            $table->unsignedDecimal('transfer_max')->nullable();
            $table->boolean('is_allowed_withdraw')->default(0);
            $table->unsignedDecimal('withdraw_min')->nullable();
            $table->unsignedDecimal('withdraw_max')->nullable();
            $table->unsignedDecimal('withdraw_fee')->nullable();
            $table->softDeletes();
            $table->index([
                'country_id',
                'code',
                'seq_no',
                'is_allowed_admin',
                'is_allowed_member',
                'is_allowed_topup',
                'is_allowed_transfer',
                'is_allowed_withdraw'
            ], 'wallet_setup_index');
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
        Schema::dropIfExists('wallet_setups');
    }
};
