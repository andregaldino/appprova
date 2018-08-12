<?php

namespace App\Providers;

use App\Repositories\Institution\InstitutionRepository;
use App\Repositories\Institution\InstitutionRepositoryContract;
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
    }
}
