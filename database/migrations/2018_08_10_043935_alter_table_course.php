<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function(Blueprint $table){
	        $table->unsignedInteger('institution_id');
	        
	        $table->foreign('institution_id')
		        ->references('id')
		        ->on('institutions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function(Blueprint $table){
        	$table->dropForeign('courses_institution_id_foreign');
        });
    }
}
