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
        Schema::create('stock_good_receives', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no', 35);
            $table->date('doc_date');
            $table->string('trans_type')->nullable();
            $table->string('status', 25)->nullable();
            $table->string('remark', 255)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->index([
                'doc_no',
                'doc_date',
                'trans_type',
                'created_by',
                'updated_by'
            ], 'stock_receive_index');
            $table->softDeletes();
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
        Schema::dropIfExists('stock_good_receives');
    }
};
