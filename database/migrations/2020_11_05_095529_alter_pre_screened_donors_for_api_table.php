<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPreScreenedDonorsForApiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_screened_donors', function (Blueprint $table) {
            $table->string('beenPregnant', 50)->nullable();
            $table->string('hadBloodTransfusion', 50)->nullable();
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
            //
        });
    }
}
