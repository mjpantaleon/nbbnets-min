<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRCpComponentCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r_cp_component_codes', function (Blueprint $table) {
            $table->tinyInteger('component_code');
            $table->char('component_abbr', 10);
            $table->string('comp_name');
            $table->char('exp_interval_type', 2);
            $table->integer('exp_interval');
            $table->decimal('min_vol', 9, 2);
            $table->decimal('max_vol', 9, 2);
            $table->integer('min_storage');
            $table->integer('max_storage');
            $table->tinyInteger('is_special');
            $table->char('disable_flg', 1);
            $table->string('created_by');
            $table->dateTime('created_dt');
            $table->string('updated_by')->nullable();
            $table->dateTime('updated_dt')->nullable();

            $table->primary('component_code');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('r_cp_component_codes');
    }
}
