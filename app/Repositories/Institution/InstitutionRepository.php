<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/9/18
 * Time: 10:18 PM
 */

namespace App\Repositories\Institution;

use App\Models\Institution;
use App\Repositories\Base\CrudContract;
use App\Repositories\Base\FilterContract;
use App\Repositories\Base\SearchContract;

class InstitutionRepository implements InstitutionRepositoryContract, CrudContract, SearchContract, FilterContract
{
	public function findByName(String $name)
	{
		return Institution::where('name', $name)->firstOrFail();
	}
	
	public function all(array $attributes = ['*'])
	{
		return Institution::get($attributes);
	}
	
	public function create(array $data)
	{
		return Institution::create($data);
	}
	
	public function update(int $id, array $data)
	{
		$institution = Institution::findOrFail($id);
		if(!$institution->update($data)){
			throw new \Exception('Error to save Institution');
		}
		return $institution;
	}
	
	public function remove(int $id)
	{
		return Institution::findOrFail($id)->delete();
	}
	
	public function searchByName(String $name)
	{
		return Institution::where('name', 'like', "%$name%")->orderBy('grade','desc')->get();
	}
	
	public function find($value)
	{
		return Institution::find($value);
	}
	
	public function allOrderedByGrade()
	{
		return Institution::orderBy('grade','desc')->get();
	}
	
	public function filterByGrade($grade)
	{
		return Institution::where('grade',$grade)->get();
	}
	
}