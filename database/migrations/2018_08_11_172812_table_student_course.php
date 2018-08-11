<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableStudentCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course', function(Blueprint $table){
        	$table->increments('id');
        	$table->unsignedInteger('student_id');
        	$table->unsignedInteger('course_id');
        	
        	$table->foreign('student_id')
		            ->references('id')
		            ->on('students')
	                ->onDelete('cascade');
        	
        	$table->foreign('course_id')
		            ->references('id')
		            ->on('courses')
	                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_course');
    }
}
