<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalHlaHnaTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_hla_hna_tests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('donation_id')->nullable();
	        $table->string('donor_sn')->nullable();
	
            $table->char('test_1', 1)->nullable();                 /* N = NEGATIVE, P = POSITIVE */
            $table->char('test_2', 1)->nullable();                 /* N = NEGATIVE, P = POSITIVE */
            $table->char('test_3', 1)->nullable();                 /* N = NEGATIVE, P = POSITIVE */

            $table->char('result', 1)->nullable();                 /* IF ANY OF TESTS HAS 'P' RESULT THEN NO GO, P */

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
        Schema::dropIfExists('additional_hla_hna_tests');
    }
}
