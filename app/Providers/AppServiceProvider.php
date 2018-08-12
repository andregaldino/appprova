<?php

namespace App\Providers;

use App\Repositories\Course\CourseRepository;
use App\Repositories\Course\CourseRepositoryContract;
use App\Repositories\Institution\InstitutionRepository;
use App\Repositories\Institution\InstitutionRepositoryContract;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentRepositoryContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->bind(
		    InstitutionRepositoryContract::class,
		    InstitutionRepository::class
	    );
	
	    $this->app->bind(
		    CourseRepositoryContract::class,
		    CourseRepository::class
	    );
	
	    $this->app->bind(
		    StudentRepositoryContract::class,
		    StudentRepository::class
	    );
    }
}
