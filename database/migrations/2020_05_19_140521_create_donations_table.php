<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->string('seqno', 16)->primary();
            $table->string('donation_id', 16)->nullable();
            $table->string('approved_by', 30)->nullable();
            $table->char('donor_sn', 16)->nullable();
            $table->string('sched_id', 30)->nullable();
            $table->char('pre_registered', 1)->nullable();
            $table->string('donation_type', 5)->nullable();
            $table->string('collection_method', 30)->nullable();
            $table->string('donation_stat', 5)->nullable();
            $table->string('facility_cd', 5)->nullable();
            $table->string('mh_pe_stat', 5)->nullable();
            $table->text('mh_pe_deferral')->nullable();
            $table->text('mh_pe_question')->nullable();
            $table->text('mh_pe_remark')->nullable();
            $table->string('collection_type', 5)->nullable();
            $table->string('collection_stat', 5)->nullable();
            $table->text('coluns_res')->nullable();
            $table->string('blood_bag', 5)->nullable();
            $table->string('created_by', 16)->nullable();
            $table->datetime('created_dt')->nullable();
            $table->string('updated_by', 16)->nullable();
            $table->datetime('updated_dt')->nullable();

            // donors.seqno = donation.donor_sn
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
        Schema::dropIfExists('donations');
    }
}
