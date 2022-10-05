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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('country_id')->nullable();
            $table->string('code', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->integer('seq_no')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->boolean('is_popup')->default(0)->comment('1 = Popup');
            $table->softDeletes();
            $table->timestamps();
            $table->index([
                'country_id',
                'code',
                'date_start',
                'date_end',
                'seq_no',
                'created_by',
                'updated_by'
            ], 'announcement_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
