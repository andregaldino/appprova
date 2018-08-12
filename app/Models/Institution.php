<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 8/9/18
 * Time: 10:39 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
	protected $fillable = ['name','grade'];
	
	public $timestamps = false;
	
	public function courses()
	{
		return $this->hasMany(Course::class);
	}
}