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
            $table->id();
            $table->string('question_type');
            $table->longText('questions'); 
            $table->unsignedBigInteger('standard_id'); 
            $table->unsignedBigInteger('subject_id'); 
            $table->unsignedBigInteger('chapter_id');
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->json('options');
            $table->string('correct_answer');
            $table->float('correct_marks'); 
            $table->longText('explanation')->nullable();
            $table->timestamps();
            $table->softDeletes(); 

            // Add foreign key constraints
            $table->foreign('standard_id')->references('id')->on('standards');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('chapter_id')->references('id')->on('chapters');
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
