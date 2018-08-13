<?php

namespace App\Http\Controllers;


use App\Repositories\Course\CourseRepositoryContract;
use App\Repositories\Student\StudentRepositoryContract;
use App\Transformers\StudentTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;

class StudentController
{
	/**
	 * @param Response $response
	 */
	public function __construct(Response $response, StudentRepositoryContract $repository, CourseRepositoryContract $repositoryCourse)
	{
		$this->response = $response;
		$this->repository = $repository;
		$this->repositoryCourse = $repositoryCourse;
	}
	
	public function index()
	{
		$students = $this->repository->all();
		
		return $this->response->withCollection($students, new StudentTransformer);
	}
	
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'grade' => 'numeric'
		]);
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		
		$student = $this->repository->create($request->all());
		
		if(!$student){
			return $this->response->errorInternalError('Student not saved');
		}
		return $this->response->withItem(
			$student,
			new StudentTransformer
		);
	}
	
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|max:255',
			'grade' => 'numeric'
		]);
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		
		$student = $this->repository->find($id);
		if(!$student){
			return $this->response->errorNotFound('Student not found');
		}
		
		$studentUpdated = $this->repository->update($id, $request->all());
		if(!$studentUpdated){
			return $this->response->errorInternalError('Student not updated');
		}
		return $this->response->withItem(
			$studentUpdated,
			new StudentTransformer
		);
	}
	
	public function addCourse(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'courses.*.grade' => 'numeric',
			'courses' => [
				'required',
				'array',
				function($attribute, $value, $fail) {
					// index arr
					$ids = array_keys($value);
					// query to check if array keys is not valid
					$coursesId = Course::whereIn('id', $ids)->count();
					if ($coursesId != count($ids))
						return $fail($attribute.' is invalid.');  // -> "quantity is invalid"
				}
			],
		]);
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		
		$student = $this->repository->subscriptionCourse($id,$request->get('course'));
		return $this->response->withItem(
			$student,
			new StudentTransformer
		);
	}
	
	public function updateGradeCourse(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'course' => 'required|exists:courses,id',
			'grade' => 'required|numeric'
		]);
		
		if ($validator->fails()) {
			return $this->response->errorWrongArgsValidator($validator);
		}
		$student = $this->repository->addGradeCourse($id,$request->get('course'), $request->get('grade'));
		if(!$student){
			$this->response->errorInternalError('Course grade not updated');
		}
		return $this->response->withArray([],200);
	}
}