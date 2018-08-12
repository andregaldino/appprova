<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Institution::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company(),
	    'grade' => $faker->numberBetween(1,5)
    ];
});

$factory->define(App\Models\Course::class, function (Faker\Generator $faker) {
	return [
		'name' => $faker->name,
		'grade' => $faker->numberBetween(1,5),
		'institution_id' => factory(App\Models\Institution::class)
	];
});


$factory->define(App\Models\Student::class, function (Faker\Generator $faker) {
	return [
		'name' => $faker->name,
	];
});
