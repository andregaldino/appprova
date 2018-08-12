<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\Student;
use App\Repositories\Course\CourseRepositoryContract;
use App\Transformers\CourseTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController
{
	/**
	 * @param Response $response
	 */
	public function __construct(Response $response, CourseRepositoryContract $repository)
	{
		$this->response = $response;
		$this->repository = $repository;
	}
	
	public function index()
	{
		$courses = $this->repository->all();
		
		return $this->response->withCollection($courses, new CourseTransformer);
	}
	
	public function searchByName($name)
	{
		$courses = $this->repository->searchByName($name);
		
		return $this->response->withCollection($courses, new CourseTransformer);
	}
	
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'grade' => 'numeric',
			'institution_id' => 'required|exists:institutions,id'
		
		]);
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		
		$course = $this->repository->create($request->all());
		
		if(!$course){
			return $this->response->errorInternalError('Course not saved');
		}
		return $this->response->withItem(
			$course,
			new CourseTransformer
		);
	}
	
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'grade' => 'numeric',
			'institution_id' => 'required|exists:institutions,id'
		
		]);
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		
		$course = $this->repository->find($id);
		if(!$course){
			return $this->response->errorNotFound('Course not found');
		}
		
		$courseUpdated = $this->repository->update($id, $request->all());
		if(!$courseUpdated){
			return $this->response->errorInternalError('Course not updated');
		}
		return $this->response->withItem(
			$courseUpdated,
			new CourseTransformer
		);
	}
	
	public function addStudents(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'students.*.grade' => 'numeric',
			'students' => [
				'required',
				'array',
				function($attribute, $value, $fail) {
					// index arr
					$ids = array_keys($value);
					// query to check if array keys is not valid
					$studentsId = Student::whereIn('id', $ids)->count();
					if ($studentsId != count($ids))
						return $fail($attribute.' is invalid.');  // -> "quantity is invalid"
				}
			],
		]);
		
		
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		
		$course = $this->repository->addStudents($id,$request->get('students'));
		return $this->response->withItem(
			$course,
			new CourseTransformer
		);
	}
}