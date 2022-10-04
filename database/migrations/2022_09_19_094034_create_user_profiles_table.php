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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('ic_no')->nullable();
            $table->unsignedInteger('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('mobile_no')->nullable();
            $table->softDeletes();
            $table->index([
                'user_id',
                'mobile_no',
                'date_of_birth',
                'ic_no'
            ], 'user_profile_index');
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
        Schema::dropIfExists('user_profiles');
    }
};
