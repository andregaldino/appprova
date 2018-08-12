<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/12/18
 * Time: 11:02 AM
 */

namespace App\Transformers;

use App\Models\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
{
	public function transform(Student $student)
	{
		return [
			'id' => (int) $student->id,
			'name' => $student->name,
		];
	}
}