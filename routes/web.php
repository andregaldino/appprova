<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['prefix' => '/api'], function () use ($router) {
	$router->group(['as' => 'institutions.', 'prefix' => 'institutions'], function() use ($router){
		$router->get('/',['as' => 'index', 'uses' => 'InstitutionController@index']);
		$router->post('/',['as' => 'store', 'uses' => 'InstitutionController@store']);
		$router->get('orderedGrade',['as' => 'orderGrade', 'uses' => 'InstitutionController@orderedGrade']);
		$router->patch('{id}/update',['as' => 'update', 'uses' => 'InstitutionController@update']);
		$router->get('searchByName/{name}',['as' => 'find.name', 'uses' => 'InstitutionController@searchByName']);
	});
	
	$router->group(['as' => 'institutions.', 'prefix' => 'institutions'], function() use ($router){
		$router->get('/',['as' => 'index', 'uses' => 'CourseController@index']);
		$router->post('/',['as' => 'store', 'uses' => 'CourseController@store']);
		$router->patch('{id}/update',['as' => 'update', 'uses' => 'CourseController@update']);
		$router->get('searchByName/{name}',['as' => 'find.name', 'uses' => 'CourseController@searchByName']);
		$router->post('subscribeStudents',['as' => 'subscribe.students', 'uses' => 'CourseController@addStudents']);
	});
	
	
});
