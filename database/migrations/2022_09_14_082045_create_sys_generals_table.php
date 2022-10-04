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
        Schema::create('sys_generals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id');
            $table->string('type', 30);
            $table->string('code', 30);
            $table->string('name', 30);
            $table->unsignedInteger('seq_no')->default(0);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'country_id',
                'code',
                'type',
                'created_by',
                'updated_by'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_generals');
    }
};
