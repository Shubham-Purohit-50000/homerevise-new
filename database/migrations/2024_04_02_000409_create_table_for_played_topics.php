<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForPlayedTopics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('played_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->text("subject");
            $table->text("chapter");
            $table->text("topic");
            $table->string("duration_minutes");
            $table->string("total_topics");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_for_played_topics');
    }
}
