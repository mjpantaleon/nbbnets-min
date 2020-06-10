<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreScreenedDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_screened_donors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('donor_sn', 16)->nullable();

            $table->string('lname', 50)->nullable();
            $table->string('fname', 50)->nullable();
            $table->string('mname', 50)->nullable();
            $table->string('name_suffix', 30)->nullable();

            $table->char('gender', 1)->nullable();              // M = MALE, F = FEMALE
            $table->date('bdate');
            $table->integer('age')->nullable();

            $table->integer('weight')->nullable();
            $table->string('nationality', 5);                   // 137
            $table->char('region_cd')->nullable();              // 13 = NCR
            $table->text('address')->nullable();
            
            $table->string('email', 50)->nullable();
            $table->string('fb', 191)->nullable();
            $table->string('mobile_no', 20)->nullable();
            
            $table->dateTime('time_to_call')->nullable();
            
            // PRE-SCREENING QUESTIONS
            $table->text('symptoms')->nullable();                   // Had the following
            $table->char('diagnosed_with_covid', 1)->nullable();    // 1 = YES, 2 = NOT SURE, 0 = NO
            $table->char('test_results',1)->nullable();             // p = POSTIVE, N = NEGATIVE, X = NO TEST RESULTS
            
            $table->date('created_dt')->nullable();                  // get from app

            // PRE-SCREENING QUESTIONS
            // $table->boolean('q_1', 1)->default(1);      // Q#1      1 = YES, 0 = NO
            // $table->text('q_2')->nullable();            // Q#2
            // $table->boolean('q_3', 1)->default(1);      // Q#3      1 = YES, 0 = NO
            // $table->boolean('q_4', 1)->default(1);      // Q#4
            // $table->boolean('q_5', 1)->default(1);      // Q#5
            // $table->boolean('q_6', 1)->default(1);      // Q#6
            // $table->boolean('q_7', 1)->default(0);      // Q#7
            // $table->boolean('q_8', 1)->default(0);      // Q#8
            // $table->boolean('q_9', 1)->default(0);      // Q#9
            // $table->boolean('q_10', 1)->default(0);      // Q#10
            // $table->binary('vein')->nullable();
            
            // APPROVAL 
            $table->boolean('approval_status', 1)->default(0);      // 1 = YES, 0 = NO
            $table->string('approved_by', 50)->nullable();
            $table->date('approval_dt')->nullable();

            // $table->timestamps();
            // donors.seqno = candidates.donor_sn
            // $table->foreign('donor_sn')->references('seqno')->on('donors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_screened_donors');
    }
}
