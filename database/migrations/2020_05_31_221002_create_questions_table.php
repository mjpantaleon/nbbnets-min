<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->char('category', 2)->nullable();
            $table->integer('header_id')->nullable();
            $table->integer('q_no')->nullable();
            $table->string('q_text')->nullable();
            $table->boolean('disable_flag')->default(0);
            $table->timestamps();

            // questions.header_id = questions_header.id 
            // $table->foreign('header_id')->references('id')->on('questions_header');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
