<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/9/18
 * Time: 10:33 PM
 */

namespace App\Repositories\Base;


interface CrudContract
{
	public function all(array $attributes = ['*']);
	
	public function create(array $data);
	
	public function update(int $id, array $data);
	
	public function remove(int $id);
	
	public function find($value);
	
}