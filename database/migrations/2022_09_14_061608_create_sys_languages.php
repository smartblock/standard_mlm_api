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
        Schema::create('sys_languages', function (Blueprint $table) {
            $table->id();
            $table->string('locale', 10);
            $table->string('code', 25);
            $table->string('name', 50);
            $table->string('avatar', 250)->nullable();
            $table->unsignedInteger('seq_no')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'locale',
                'code',
                'seq_no',
                'created_by',
                'updated_by'
            ], 'language_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_languages');
    }
};
