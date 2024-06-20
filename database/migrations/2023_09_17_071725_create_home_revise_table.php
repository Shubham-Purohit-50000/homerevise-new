<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique('users_email_unique');
            $table->string('phone', 15)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Schema::create('personal_access_tokens', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->string('tokenable_type');
        //     $table->unsignedBigInteger('tokenable_id');
        //     $table->string('name');
        //     $table->string('token', 64)->unique();
        //     $table->text('abilities')->nullable();
        //     $table->timestamp('last_used_at')->nullable();
        //     $table->timestamps();

        //     $table->index(['tokenable_type', 'tokenable_id']);
        // });

        Schema::create('states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('folder_name', 250);
            $table->timestamps();
        });

        Schema::create('boards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('state_id')->index('boards_state_id_foreign');
            $table->string('folder_name', 250);
            $table->timestamps();
        });

        Schema::create('mediums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('board_id')->index('mediums_board_id_foreign');
            $table->string('folder_name', 250);
            $table->timestamps();
        });

        Schema::create('standards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('medium_id')->index('standards_medium_id_foreign');
            $table->string('folder_name', 250);
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('standard_id')->index('subjects_standard_id_foreign');
            $table->string('folder_name', 250);
            $table->timestamps();
        });

        Schema::create('chapters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('subject_id')->index('chapters_subject_id_foreign');
            $table->string('folder_name', 250);
            $table->timestamps();
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('heading');
            $table->text('body');
            $table->unsignedBigInteger('chapter_id')->index('topics_chapter_id_foreign');
            $table->string('primary_key', 250);
            $table->string('secondary_key', 250);
            $table->string('file_name', 250);
            $table->string('folder_name', 250)->nullable();
            $table->timestamps();
        });

        Schema::create('subtopics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('heading');
            $table->text('body');
            $table->unsignedBigInteger('topic_id')->index('subtopics_topic_id_foreign');
            $table->timestamps();
            $table->string('primary_key', 250);
            $table->string('secondary_key', 250);
            $table->string('file_name', 250);
            $table->string('folder_name', 250)->nullable();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('standard_id')->nullable()->index('courses_standard_id_foreign');
            $table->unsignedBigInteger('subject_id')->nullable()->index('courses_subject_id_foreign');
            $table->integer('duration');
            $table->boolean('status');
            $table->string('folder_name', 250)->nullable();
            $table->string('device_type')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
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
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('activations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id')->index('activations_course_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('activations_user_id_foreign');
            $table->string('activation_key');
            $table->dateTime('activation_time')->nullable();
            $table->timestamps();
        });


        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_option', 250);
            $table->text('value')->nullable();
            $table->timestamps(0); // Use 0 to prevent Laravel from automatically managing created_at and updated_at
        });


        Schema::table('activations', function (Blueprint $table) {
            $table->foreign(['course_id'])->references(['id'])->on('courses')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onDelete('cascade');
        });

        Schema::table('boards', function (Blueprint $table) {
            $table->foreign(['state_id'])->references(['id'])->on('states')->onDelete('cascade');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->foreign(['subject_id'], 'chapters_subject_id_foreign')->references(['id'])->on('subjects')->onDelete('cascade');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreign(['standard_id'])->references(['id'])->on('standards')->onDelete('cascade');
            $table->foreign(['subject_id'])->references(['id'])->on('subjects')->onDelete('cascade');
        });

        Schema::table('mediums', function (Blueprint $table) {
            $table->foreign(['board_id'])->references(['id'])->on('boards')->onDelete('cascade');
        });

        Schema::table('standards', function (Blueprint $table) {
            $table->foreign(['medium_id'])->references(['id'])->on('mediums')->onDelete('cascade');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->foreign(['standard_id'])->references(['id'])->on('standards')->onDelete('cascade');
        });

        Schema::table('subtopics', function (Blueprint $table) {
            $table->foreign(['topic_id'])->references(['id'])->on('topics')->onDelete('cascade');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->foreign(['chapter_id'])->references(['id'])->on('chapters')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign('topics_chapter_id_foreign');
        });

        Schema::table('subtopics', function (Blueprint $table) {
            $table->dropForeign('subtopics_topic_id_foreign');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign('subjects_standard_id_foreign');
        });

        Schema::table('standards', function (Blueprint $table) {
            $table->dropForeign('standards_medium_id_foreign');
        });

        Schema::table('mediums', function (Blueprint $table) {
            $table->dropForeign('mediums_board_id_foreign');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign('courses_subject_id_foreign');
            $table->dropForeign('courses_standard_id_foreign');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign('chapters_standard_id_foreign');
        });

        Schema::table('boards', function (Blueprint $table) {
            $table->dropForeign('boards_state_id_foreign');
        });

        Schema::table('activations', function (Blueprint $table) {
            $table->dropForeign('activations_user_id_foreign');
            $table->dropForeign('activations_course_id_foreign');
        });

        Schema::dropIfExists('settings');

        Schema::dropIfExists('users');

        Schema::dropIfExists('topics');

        Schema::dropIfExists('subtopics');

        Schema::dropIfExists('subjects');

        Schema::dropIfExists('states');

        Schema::dropIfExists('standards');

        Schema::dropIfExists('personal_access_tokens');

        Schema::dropIfExists('password_resets');

        Schema::dropIfExists('mediums');

        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('courses');

        Schema::dropIfExists('chapters');

        Schema::dropIfExists('boards');

        Schema::dropIfExists('admins');

        Schema::dropIfExists('activations');
    }
};
