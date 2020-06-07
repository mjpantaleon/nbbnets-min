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
            $table->string('donor_id', 16)->nullable();
            $table->string('lname', 50)->nullable();
            $table->string('fname', 50)->nullable();
            $table->string('mname', 50)->nullable();
            $table->string('name_suffix', 30)->nullable();
            $table->date('bdate');
            $table->char('gender', 1)->nullable();              // M = MALE, F = FEMALE
            $table->char('civil_stat', 3)->nullable();
            $table->string('tel_no', 20)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('nationality', 5); # 137
            $table->string('occupation', 100)->nullable();

            // ADDRESS
            $table->string('home_region', 2)->nullable();
            $table->text('address')->nullable();
            // $table->text('home_no_st_blk', 100)->nullable();
            // $table->string('home_brgy', 9)->nullable();
            // $table->string('home_citymun', 6)->nullable();
            // $table->string('home_prov', 4)->nullable();
            // $table->string('home_region', 2)->nullable();
            // $table->string('home_zip', 10)->nullable();
            
            // EXPOSURE
            // $table->char('known_exposure', 1)->nullable();      // Y = YES, N = NO, X = UNKNOWN
            // $table->date('date_of_known_contact')->nullable();  // IF YES THEN GET DATE
            
            // PATIENT HISTORY
            // $table->char('clinical_status', 1)->nullable();     // IP = IN-PATIENT, OP = OUT-PATIENT, D = DIED, DC = DISCHARGED, X = UNKNOWN
            // $table->date('onset_of_illess_dt')->nullable();
            // $table->date('admission_dt')->nullable();
            // $table->decimal('fever', 6,2)->nullable();
            // $table->string('symptoms', 100)->nullable();         // COUGH, SORE THROAT, COLDS, DIFFICULTY OF BREATHING
            // $table->string('others_symptoms', 250)->nullable();
            // $table->boolean('has_other_illness', 1)->dafault(0);        // 1 = YES, 0 = NO
            // $table->string('other_illness', 250)->nullable(); // IF YES ON OTHER ILLNESS
            // $table->boolean('has_xray', 1)->default(1);         // 1 = YES, 0 = NO
            // $table->date('xray_dt')->nullable();                // IF HAS XRAY
            // $table->char('is_pregnant', 1)->nullable();         // 1 = YES, 0 = NO, 2 = LMP
            // $table->char('cxr_result', 1)->nullable();          // 1 = YES, 0 = NO, 2 = PENDING
            // $table->string('other_findings')->nullable();
            
            // PRE-SCREENING QUESTIONS
            $table->boolean('q_1', 1)->default(1);      // Q#1      1 = YES, 0 = NO
            $table->text('q_2')->nullable();            // Q#2
            $table->boolean('q_3', 1)->default(1);      // Q#3      1 = YES, 0 = NO
            $table->boolean('q_4', 1)->default(1);      // Q#4
            $table->boolean('q_5', 1)->default(1);      // Q#5
            $table->boolean('q_6', 1)->default(1);      // Q#6
            $table->boolean('q_7', 1)->default(1);      // Q#7
            $table->boolean('q_8', 1)->default(1);      // Q#8
            $table->boolean('q_9', 1)->default(1);      // Q#9
            $table->boolean('q_10', 1)->default(1);      // Q#10
            $table->binary('vein')->nullable();

            // ITPCR TEST
            $table->boolean('positive_with_itpcr')->default(1); // 1 = YES, 0 = NO
            $table->char('igm_level', 1)->default('N');           // P = POSITIVE, N = NEGATIVE
            $table->char('igg_level', 1)->default('P');           // P = POSITIVE, N = NEGATIVE
            
            // DISCHARGE DETAILS
            // $table->date('discharge_dt')->nullable();
            // $table->string('condition_on_discharge', 30)->nullable();   // DIED, IMPROVED, RECOVERED, TRANSFERED, ABSCONDED

            $table->date('screen_dt')->nullable();                  // date screened from app
            $table->boolean('approval_status', 1)->default(0);      // 1 = YES, 0 = NO

            // $table->timestamps();
            // donors.seqno = candidates.donor_sn
            $table->foreign('donor_sn')->references('seqno')->on('donors');
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
