<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIggFieldToPreScreenedDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_screened_donors', function (Blueprint $table) {
            $table->char('igg_result', 1)->nullable();
            $table->integer('igg_value', 5)->nullable();

            $table->string('igg_result_by', 50)->nullable();
            $table->dateTime('igg_result_dt')->nullable(); 

            $table->string('igg_approved_by', 50)->nullable();
            $table->dateTime('igg_approval_dt')->nullable();                 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_screened_donors', function (Blueprint $table) {
            $table->dropColumn('igg_result');
            $table->dropColumn('igg_value');
            $table->dropColumn('igg_result_by');
            $table->dropColumn('igg_result_dt');
            $table->dropColumn('igg_approved_by');
            $table->dropColumn('igg_approval_dt');
        });
    }
}
