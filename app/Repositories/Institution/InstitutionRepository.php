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

class InstitutionRepository implements InstitutionRepositoryContract, CrudContract
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
		return Institution::findOrFail($id)->update($data);
	}
	
	public function remove(int $id)
	{
		return Institution::findOrFail($id)->delete();
	}
}