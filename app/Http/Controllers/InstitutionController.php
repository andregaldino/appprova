<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/12/18
 * Time: 11:00 AM
 */

namespace App\Http\Controllers;


use App\Repositories\Institution\InstitutionRepositoryContract;
use App\Transformers\InstitutionTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstitutionController
{
	/**
	 * @param Response $response
	 */
	public function __construct(Response $response, InstitutionRepositoryContract $repository)
	{
		$this->response = $response;
		$this->repository = $repository;
	}
	
	public function index()
	{
		$institutions = $this->repository->all();

		return $this->response->withCollection($institutions, new InstitutionTransformer);
	}
	
	public function orderedGrade()
	{
		$institutions = $this->repository->allOrderedByGrade();
		
		return $this->response->withCollection($institutions, new InstitutionTransformer);
	}
	
	public function searchByName($name)
	{
		$institutions = $this->repository->searchByName($name);
		
		return $this->response->withCollection($institutions, new InstitutionTransformer);
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
		
		
		$institution = $this->repository->create($request->all());
		
		if(!$institution){
			return $this->response->errorInternalError('Institution not saved');
		}
		return $this->response->withItem(
			$institution,
			new InstitutionTransformer
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
		
		$institution = $this->repository->find($id);
		if(!$institution){
			return $this->response->errorNotFound('Institution not found');
		}
		
		$institutionUpdated = $this->repository->update($id, $request->all());
		if(!$institutionUpdated){
			return $this->response->errorInternalError('Institution not updated');
		}
		return $this->response->withItem(
			$institutionUpdated,
			new InstitutionTransformer
		);
	}
}