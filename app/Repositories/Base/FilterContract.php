<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/12/18
 * Time: 9:47 AM
 */

namespace App\Repositories\Base;


interface FilterContract
{
	public function filterByGrade($grade);
}