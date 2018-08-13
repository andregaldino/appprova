<?php

namespace App\Repositories\Course;

use App\Models\Course;
use App\Repositories\Base\CrudContract;
use App\Repositories\Base\FilterContract;
use App\Repositories\Base\SearchContract;

class CourseRepository implements CourseRepositoryContract, CrudContract, SearchContract, FilterContract
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
		$course = Course::findOrFail($id);
		if(!$course->update($data)){
			throw new \Exception('Error to save Course');
		}
		return $course;
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
	
	public function filterByGrade($grade)
	{
		return Course::where('grade', $grade)->get();
	}
	
	public function averageGrade()
	{
		return Course::join('student_course','courses.id', '=', 'student_course.course_id')
			->selectRaw('courses.*, AVG(student_course.grade)')
			->groupBy('courses.id')
			->get();
	}
	
}