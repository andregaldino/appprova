<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/10/18
 * Time: 9:45 PM
 */

namespace App\Repositories\Student;


use App\Models\Student;
use App\Repositories\Base\CrudContract;
use App\Repositories\Course\CourseRepositoryContract;

class StudentRepository implements StudentRepositoryContract, CrudContract
{
	public function all(array $attributes = ['*'])
	{
		return Student::get($attributes);
	}
	
	public function create(array $data)
	{
		return Student::create($data);
	}
	
	public function update(int $id, array $data)
	{
		return Student::findOrFail($id)->update($data);
	}
	
	public function remove(int $id)
	{
		return Student::findOrFail($id)->delete();
	}
	
	public function subscriptionCourse($id, $course)
	{
		$student = Student::findOrFail($id);
		
		$student->courses()->save($course);
		
		return $student;
	}
	
	public function find($value)
	{
		return Student::find($value);
	}
	
	public function addGradeCourse($id, $course, $grade): bool
	{
		$student = Student::findOrFail($id);
		return ($student->courses()->updateExistingPivot($course, ['grade' => $grade])) ? true : false;
	}
	
}