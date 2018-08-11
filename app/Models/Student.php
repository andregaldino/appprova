<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	protected $fillable = ['name'];
	
	public $timestamps = false;
	
	public function courses()
	{
		return $this->belongsToMany('App\Models\Course', 'student_course')->withPivot('grade');
	}
}

