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
        Schema::create('sys_countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 15);
            $table->string('name', 50);
            $table->string('territory_type', 20);
            $table->string('calling_no_prefix', 10)->nullable()->index();
            $table->string('avatar', 255)->nullable();
            $table->string('prefer_language_code', 15)->nullable();
            $table->string('currency_code', 15)->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'code',
                'prefer_language_code',
                'currency_code',
                'parent_id',
                'created_by',
                'updated_by'
            ], 'country_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_countries');
    }
};
