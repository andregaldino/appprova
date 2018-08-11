<?php

namespace App\Repositories\Course;

use App\Models\Course;
use App\Repositories\Base\CrudContract;
use App\Repositories\Base\SearchContract;

class CourseRepository implements CourseRepositoryContract, CrudContract, SearchContract
{
	public function findByName(String $name)
	{
		return Course::where('name', $name)->firstOrFail();
	}
	
	public function all(array $attributes = ['*'])
	{
		return Course::get($attributes);
	}
	
	public function create(array $data)
	{
		return Course::create($data);
	}
	
	public function update(int $id, array $data)
	{
		return Course::findOrFail($id)->update($data);
	}
	
	public function remove(int $id)
	{
		return Course::findOrFail($id)->delete();
	}
	
	public function searchByName(String $name)
	{
		return Course::where('name', 'like', "%$name%")->get();
	}
	
	public function find($value)
	{
		return Course::find($value);
	}
	
	public function addStudents($id, $students)
	{
		$course = Course::findOrFail($id);
		$course->students()->attach($students);
		return $course;
	}
	
	public function averageGradeByCourse($course)
	{
		return round(Course::findOrFail($course)->students()->avg('grade'), 1);
	}
}