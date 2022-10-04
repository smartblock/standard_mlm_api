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
        Schema::create('wallet_transfers', function (Blueprint $table) {
            $table->id();
            $table->date('doc_date')->nullable();
            $table->string('doc_no', 25)->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_id_to');
            $table->unsignedInteger('wallet_id')->nullable();
            $table->unsignedInteger('wallet_id_to')->nullable();
            $table->unsignedDecimal('transfer_amount', 25, 10)->nullable();
            $table->unsignedDecimal('rate', 25, 10)->default(1);
            $table->unsignedDecimal('transfer_amount_to', 25, 10)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->index([
                'user_id',
                'user_id_to',
                'wallet_id',
                'wallet_id_to',
                'created_by',
                'updated_by'
            ], 'transfer_index');
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
        Schema::dropIfExists('wallet_transfers');
    }
};
