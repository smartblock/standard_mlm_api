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
        Schema::create('announcement_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('announcement_id')->nullable();
            $table->unsignedInteger('language_id')->nullable();
            $table->string('title', 255);
            $table->longText('description')->nullable();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->index([
                'announcement_id',
                'language_id',
                'created_by',
                'updated_by'
            ], 'announcement_detail_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcement_details');
    }
};
