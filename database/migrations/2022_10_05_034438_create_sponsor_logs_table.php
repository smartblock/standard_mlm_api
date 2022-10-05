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
        Schema::create('sponsor_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sponsor_id_from');
            $table->string('sponsor_from', 50)->nullable();
            $table->unsignedInteger('sponsor_id_to');
            $table->string('sponsor_to', 50)->nullable();
            $table->index([
                'user_id',
                'sponsor_id_from',
                'sponsor_from',
                'sponsor_id_to',
                'sponsor_to'
            ], 'sponsor_log_index');
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
        Schema::dropIfExists('sponsor_logs');
    }
};
