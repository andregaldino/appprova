<?php

namespace App\Repositories\Course;


interface CourseRepositoryContract
{
	public function findByName(String $name);
}