<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
	protected $fillable = ['name','institution_id','grade'];
	
	public $timestamps = false;
	
	public function students()
	{
		return $this->belongsToMany('App\Models\Student','student_course');
	}
}