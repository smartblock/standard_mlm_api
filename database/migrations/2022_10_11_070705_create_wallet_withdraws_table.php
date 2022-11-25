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
        Schema::create('wallet_withdraws', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no', 30)->nullable();
            $table->string('doc_date')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('user_bank_id')->nullable();
            $table->unsignedInteger('wallet_id')->nullable();
            $table->string('currency_code', 30)->nullable();
            $table->string('transaction_type')->nullable();
            $table->string('status', 25)->nullable();
            $table->decimal('amount', 25, 10)->nullable();
            $table->decimal('fee', 25, 10)->nullable();
            $table->string('converted_currency_code', 30)->nullable();
            $table->decimal('conversion_rate', 25, 10)->nullable();
            $table->decimal('converted_amount', 25, 10)->nullable();
            $table->decimal('converted_fee', 25, 10)->nullable();
            $table->longText('remark')->nullable();
            $table->longText('additional_msg')->nullable();
            $table->unsignedInteger('processed_by')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->unsignedInteger('rejected_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'doc_no',
                'doc_date',
                'user_id',
                'user_bank_id',
                'wallet_id',
                'currency_code',
                'transaction_type',
                'status',
                'converted_currency_code',
                'processed_by',
                'rejected_by',
                'approved_by'
            ], 'withdraw_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_withdraws');
    }
};
