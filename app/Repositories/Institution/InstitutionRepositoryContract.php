<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/9/18
 * Time: 10:19 PM
 */

namespace App\Repositories\Institution;


interface InstitutionRepositoryContract
{
	public function findByName(String $name);
}