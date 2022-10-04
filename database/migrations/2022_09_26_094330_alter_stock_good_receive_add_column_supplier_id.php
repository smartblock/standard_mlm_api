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
        Schema::table('stock_good_receives', function (Blueprint $table) {
            $table->unsignedInteger('supplier_id')->nullable()->index()->after('doc_date');
            $table->unsignedInteger('stock_id')->nullable()->index()->after('doc_date');
            $table->string('reason')->after('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_good_receives', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
            $table->dropColumn('stock_id');
            $table->dropColumn('reason');
        });
    }
};
