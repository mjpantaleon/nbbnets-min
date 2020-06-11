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
            $table->bigIncrements('id');                                // auto increment
            $table->string('donor_sn', 16)->nullable();                 // tag this record after approval

            $table->string('last_name', 50)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('name_suffix', 30)->nullable();

            $table->string('nationality', 191)->nullable();             // 137 = FILIPINO

            $table->char('gender', 1)->nullable();                      // M = MALE, F = FEMALE
            $table->date('bdate')->nullable();                          
            $table->integer('age')->nullable();                         // auto compute from date
            
            $table->integer('weight')->nullable();

            $table->text('address')->nullable();
            
            $table->string('email', 191)->nullable();
            $table->string('fb', 191)->nullable();
            $table->string('mobile_no', 20)->nullable();
            
            $table->dateTime('time_availability')->nullable();
    
            $table->char('first_answer',1)->nullable();                 // "first_answer" : 0 = yes, 1 = not sure, 2 = no
            $table->string('second_answer',191)->nullable();            // "second_answer" : a = Initial positive, b = Repeat negative, c = no test result
            $table->string('not_sure_answer', 191)->nullable();         // if test_results_available is equal to '1 = not sure'
            $table->date('created_dt')->nullable();                     // get from app

            // APPROVAL 
            $table->boolean('status', 1)->default(0);                   // 1 = YES, "" / 0 = NO

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
