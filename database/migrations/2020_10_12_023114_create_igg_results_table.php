<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIggResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('igg_results', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('donation_id')->nullable();
            $table->string('donor_sn', 16)->nullable();

            $table->char('igg', 1)->nullable();                 /* N = NEGATIVE, P = POSITIVE */
            $table->string('cut_off_val')->nullable();          /* PASSING VALUE 2+ */

            $table->string('result_by', 50)->nullable();
            $table->dateTime('result_dt')->nullable(); 

            $table->string('approved_by', 50)->nullable();
            $table->dateTime('approval_dt')->nullable(); 
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('igg_results');
    }
}
