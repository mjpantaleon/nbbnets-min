<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            // $table->id();
            $table->binary('donor_photo')->nullable();
            $table->string('seqno', 16)->primary();
            $table->string('donor_id', 16)->nullable();
            $table->string('name_suffix', 30)->nullable();
            $table->string('lname', 50)->nullable();
            $table->string('fname', 50)->nullable();
            $table->string('mname', 50)->nullable();
            $table->date('bdate');
            $table->char('gender', 2)->nullable();
            $table->char('civil_stat', 3)->nullable();
            $table->string('tel_no', 20)->nullable();
            $table->string('mobile_no', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('nationality', 5); # 137
            $table->string('occupation', 100)->nullable();
            $table->text('home_no_st_blk', 100)->nullable();
            $table->string('home_brgy', 9)->nullable();
            $table->string('home_citymun', 6)->nullable();
            $table->string('home_prov', 4)->nullable();
            $table->string('home_region', 2)->nullable();
            $table->string('home_zip', 10)->nullable();
            $table->text('office_no_st_blk', 100)->nullable();
            $table->string('office_brgy', 9)->nullable();
            $table->string('office_citymun', 6)->nullable();
            $table->string('office_prov', 4)->nullable();
            $table->string('office_region', 2)->nullable();
            $table->string('office_zip', 10)->nullable();
            $table->char('donation_stat', 1)->nullable();
            $table->char('donor_stat', 3)->nullable();
            $table->string('deferral_basis', 5)->nullable();
            $table->string('facility_cd', 6)->nullable();
            $table->binary('lfinger')->nullable();
            $table->binary('rfinger')->nullable();
            $table->string('created_by', 16)->nullable();
            $table->datetime('created_dt')->nullable();
            $table->string('updated_by', 16)->nullable();
            $table->datetime('updated_dt')->nullable();
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
        Schema::dropIfExists('donors');
    }
}
