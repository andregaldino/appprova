<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/10/18
 * Time: 1:08 AM
 */

namespace App\Repositories\Base;


interface SearchContract
{
	public function searchByName(String $name);
}