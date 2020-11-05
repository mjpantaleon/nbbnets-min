<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalHnaTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_hna_tests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('donation_id')->nullable();

            $table->char('hna_hnl_result', 1)->nullable();                 /* N = NEGATIVE, P = POSITIVE */

            $table->string('result_by', 50)->nullable();
            $table->dateTime('result_dt')->nullable(); 

            $table->string('approved_by', 50)->nullable();
            $table->dateTime('approval_dt')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_hna_tests');
    }
}
