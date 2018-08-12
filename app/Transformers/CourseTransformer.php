<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/12/18
 * Time: 11:02 AM
 */

namespace App\Transformers;


use App\Models\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
	protected $defaultIncludes = [
		'institution'
	];
	
	public function transform(Course $course)
	{
		return [
			'id' => (int) $course->id,
			'name' => $course->name,
			'grade' => (int) $course->grade,
		];
	}
	
	public function includeInstitution(Course $course){
		return $this->item($course->institution, new InstitutionTransformer);
	}
}