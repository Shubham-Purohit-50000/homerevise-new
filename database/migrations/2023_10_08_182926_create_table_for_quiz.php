<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForQuiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); 
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('standard_id')->nullable(); 
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->text('quiz_desc')->nullable();
            $table->boolean('marks_type');
            $table->string('manual_marks');
            $table->boolean('negative_marking_type');
            $table->float('negative_marking'); 
            $table->integer('total_quiz_time'); 
            $table->boolean('is_published');
            $table->timestamps();
            $table->softDeletes(); 

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
        Schema::dropIfExists('quiz');
    }
}
