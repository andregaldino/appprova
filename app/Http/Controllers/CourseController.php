<?php

namespace App\Http\Controllers;


use App\Repositories\Course\CourseRepositoryContract;
use App\Transformers\CourseTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;

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
		$course = $this->repository->addStudents($id,$request->all());
		return $this->response->withItem(
			$course,
			new CourseTransformer
		);
	}
}