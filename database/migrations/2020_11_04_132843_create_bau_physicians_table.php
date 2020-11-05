<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBauPhysiciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bau_physicians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('request_id',30)->nullable();
            $table->string('fname', 30)->nullable();
            $table->string('mname', 30)->nullable();
            $table->string('lname', 30)->nullable();
            $table->string('name_suffix', 30)->nullable();
            $table->string('mobile_num', 80)->nullable();
            $table->string('email', 80)->nullable();
            $table->boolean('disable_flag', 1)->default('0');       // 0 = N, 1 = Y
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
        Schema::dropIfExists('bau_physicians');
    }
}
