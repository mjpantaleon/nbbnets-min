<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePheresisBloodLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pheresis_blood_labels', function (Blueprint $table) {
            $table->char('label_no', 16);
            $table->string('facility_cd', 6);
            $table->dateTime('label_dt');
            $table->string('label_by', 16);
            $table->string('donation_id', 20);
            $table->string('source_donation_id', 16);
            $table->string('component_cd', 5);
            $table->integer('reprint_count')->nullable()->default(0);
            $table->text('reason')->nullable();

            $table->primary('label_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pheresis_blood_labels');
    }
}
