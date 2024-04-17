<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableForStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) { 
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone', 15)->nullable();
            $table->boolean('isVerified')->default(false);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('device_id', 500)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('standard', 500)->nullable();
            $table->integer('course_extended_days')->nullable(); 
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
