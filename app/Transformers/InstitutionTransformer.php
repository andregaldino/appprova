<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/12/18
 * Time: 11:02 AM
 */

namespace App\Transformers;


use App\Models\Institution;
use League\Fractal\TransformerAbstract;

class InstitutionTransformer extends TransformerAbstract
{
	public function transform(Institution $institution)
	{
		return [
			'id' => (int) $institution->id,
			'name' => $institution->name,
			'grade' => (int) $institution->grade,
			'courses' => $institution->courses()->count()
		];
	}
}