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
        Schema::create('sys_doc_numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('doc_type', 25)->unique();
            $table->string('doc_no_prefix', 25);
            $table->unsignedInteger('doc_length')->default(8);
            $table->unsignedInteger('start_no')->default(1);
            $table->string('running_type')->default(0)->comment('0 = Increment, 1 = Random');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->index([
                'country_id',
                'doc_type',
                'doc_no_prefix',
                'running_type',
                'created_by',
                'updated_by'
            ], 'doc_number_index');
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
        Schema::dropIfExists('sys_doc_numbers');
    }
};
