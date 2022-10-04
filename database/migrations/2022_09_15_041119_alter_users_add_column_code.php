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
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 50)->unique()->nullable()->after('status')->index();
            $table->string('code', 25)->unique()->index()->after('id')->nullable();
            $table->unsignedInteger('country_id')->nullable()->after('username')->index();
            $table->string('secondary_pin', 255)->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referral_code');
            $table->dropColumn('code');
            $table->dropColumn('country_id');
            $table->dropColumn('secondary_pin');
        });
    }
};
